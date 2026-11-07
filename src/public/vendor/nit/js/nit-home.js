/* Home page: 3D network globe (Three.js) and scroll motion (GSAP). Both are optional extras. */
(function () {
    'use strict';
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function cssColor(name, fallback) {
        return getComputedStyle(document.documentElement).getPropertyValue(name).trim() || fallback;
    }

    function initGlobe() {
        var canvas = document.querySelector('[data-hero-globe]');
        if (!canvas || !window.THREE || !canvas.parentElement.offsetWidth) return;
        var renderer;
        try {
            renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
        } catch (e) {
            return;
        }
        document.documentElement.classList.add('has-globe');
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));

        var scene = new THREE.Scene();
        var camera = new THREE.PerspectiveCamera(42, 1, 0.1, 50);
        camera.position.z = 5.9;
        var globe = new THREE.Group();
        globe.rotation.x = 0.3;
        globe.rotation.y = -3.45;
        scene.add(globe);

        var radius = 1.7, sky = new THREE.Color(cssColor('--nit-sky', '#2a9be0')), accent = new THREE.Color(cssColor('--nit-accent', '#0f9f8f'));

        // Dark core hides the far side of the point cloud
        var coreMaterial = new THREE.MeshBasicMaterial({ color: 0x0a1730, transparent: true, opacity: 0.94 });
        globe.add(new THREE.Mesh(new THREE.SphereGeometry(radius - 0.02, 48, 48), coreMaterial));
        var landMaterial = new THREE.PointsMaterial({ color: sky, size: 0.021, transparent: true, opacity: 1 });
        var arcMaterial = new THREE.LineBasicMaterial({ color: sky, transparent: true, opacity: 0.34 });

        function fromLatLon(lat, lon, size) {
            var phi = (90 - lat) * Math.PI / 180, theta = (lon + 180) * Math.PI / 180;
            return new THREE.Vector3(-Math.sin(phi) * Math.cos(theta), Math.cos(phi), Math.sin(phi) * Math.sin(theta)).multiplyScalar(size);
        }

        // Land as dots: the mask is white on water
        function addLand(mask) {
            var count = 26000, golden = Math.PI * (3 - Math.sqrt(5)), points = [];
            for (var i = 0; i < count; i++) {
                var y = 1 - (i / (count - 1)) * 2, r = Math.sqrt(1 - y * y), a = golden * i;
                var x = Math.cos(a) * r, z = Math.sin(a) * r;
                var lat = Math.asin(y) * 180 / Math.PI, lon = Math.atan2(z, -x) * 180 / Math.PI - 180;
                lon = ((lon + 540) % 360) - 180;
                if (mask && !mask(lat, lon)) continue;
                points.push(x * radius, y * radius, z * radius);
            }
            var cloud = new THREE.BufferGeometry();
            cloud.setAttribute('position', new THREE.Float32BufferAttribute(points, 3));
            globe.add(new THREE.Points(cloud, landMaterial));
            if (reduced) frame();
        }
        var image = new Image();
        image.onload = function () {
            var board = document.createElement('canvas');
            board.width = 720;
            board.height = 360;
            var context = board.getContext('2d', { willReadFrequently: true });
            context.drawImage(image, 0, 0, board.width, board.height);
            var pixels = context.getImageData(0, 0, board.width, board.height).data;
            addLand(function (lat, lon) {
                var col = Math.min(719, Math.floor((lon + 180) * 2)), row = Math.min(359, Math.floor((90 - lat) * 2));
                return pixels[(row * 720 + col) * 4] < 128;
            });
        };
        image.onerror = function () { addLand(null); };
        image.src = canvas.getAttribute('data-mask');

        // Connections from Dhaka, each with a travelling pulse
        var dhaka = fromLatLon(23.8, 90.4, radius), pulses = [], pulseGeometry = new THREE.SphereGeometry(0.018, 12, 12);
        var dotMaterial = new THREE.MeshBasicMaterial({ color: 0xffffff }), homeMaterial = new THREE.MeshBasicMaterial({ color: 0x7ff0dc });
        var pathSteps = 240, trailSteps = 46, restFrames = 70, trailColor = new THREE.Color(0x8ff5e6);
        function glowTexture(centre, edge) {
            var board = document.createElement('canvas');
            board.width = board.height = 64;
            var paint = board.getContext('2d'), fill = paint.createRadialGradient(32, 32, 0, 32, 32, 32);
            fill.addColorStop(0, centre);
            fill.addColorStop(0.22, 'rgba(' + edge + ', .85)');
            fill.addColorStop(1, 'rgba(' + edge + ', 0)');
            paint.fillStyle = fill;
            paint.fillRect(0, 0, 64, 64);
            return new THREE.CanvasTexture(board);
        }
        var glows = { dark: glowTexture('#ffffff', '143, 245, 230'), light: glowTexture('#0f9f8f', '20, 116, 184') };

        var home = new THREE.Mesh(new THREE.SphereGeometry(0.05, 16, 16), homeMaterial);
        home.position.copy(dhaka);
        globe.add(home);
        [[1.35, 103.8], [25.2, 55.3], [51.5, -0.1], [50.1, 8.7], [40.7, -74], [35.7, 139.7], [-33.9, 151.2], [37.8, -122.4], [-23.5, -46.6]].forEach(function (city, index) {
            var to = fromLatLon(city[0], city[1], radius);
            var lift = 1.12 + dhaka.distanceTo(to) * 0.13;
            var curve = new THREE.QuadraticBezierCurve3(dhaka, dhaka.clone().add(to).multiplyScalar(0.5).normalize().multiplyScalar(radius * lift), to);
            var path = curve.getPoints(pathSteps);
            globe.add(new THREE.Line(new THREE.BufferGeometry().setFromPoints(path), arcMaterial));

            var dot = new THREE.Mesh(pulseGeometry, dotMaterial);
            dot.position.copy(to);
            globe.add(dot);

            // Bright head, fading tail and a ripple on arrival; additive blending fades black to nothing
            var trail = new THREE.BufferGeometry();
            trail.setAttribute('position', new THREE.BufferAttribute(new Float32Array(trailSteps * 3), 3));
            var shades = new Float32Array(trailSteps * 3);
            trail.setAttribute('color', new THREE.BufferAttribute(shades, 3));
            var tail = new THREE.Line(trail, new THREE.LineBasicMaterial({ vertexColors: true, blending: THREE.AdditiveBlending, transparent: true, depthWrite: false }));
            tail.frustumCulled = false;
            globe.add(tail);

            var head = new THREE.Sprite(new THREE.SpriteMaterial({ map: glows.dark, blending: THREE.AdditiveBlending, transparent: true, depthWrite: false }));
            head.scale.setScalar(0.1);
            globe.add(head);

            var ripple = new THREE.Mesh(new THREE.RingGeometry(0.03, 0.038, 40), new THREE.MeshBasicMaterial({ color: 0x7ff0dc, transparent: true, opacity: 0, side: THREE.DoubleSide, depthWrite: false }));
            ripple.position.copy(to).multiplyScalar(1.004);
            ripple.quaternion.setFromUnitVectors(new THREE.Vector3(0, 0, 1), to.clone().normalize());
            globe.add(ripple);

            pulses.push({ path: path, tail: tail, head: head, ripple: ripple, clock: index * 37, duration: 120 + dhaka.distanceTo(to) * 42 });
        });

        // Orbit rings
        var rings = new THREE.Group();
        [2.02].forEach(function (size, index) {
            var ring = new THREE.Mesh(new THREE.TorusGeometry(size, 0.004, 8, 160), new THREE.MeshBasicMaterial({ color: sky, transparent: true, opacity: 0.3 }));
            ring.rotation.x = 1.25 + index * 0.3;
            ring.rotation.y = index ? -0.4 : 0.35;
            rings.add(ring);
        });
        scene.add(rings);

        // Light theme: white world with blue land. Dark theme: glowing night view.
        function applyTheme() {
            var dark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            var core = new THREE.Color(dark ? 0x0a1730 : 0xf3f8fd), tip = new THREE.Color(dark ? 0x8ff5e6 : 0x0f9f8f);
            var blending = dark ? THREE.AdditiveBlending : THREE.NormalBlending;
            coreMaterial.color.copy(core);
            coreMaterial.opacity = dark ? 0.94 : 0.97;
            landMaterial.color.set(dark ? 0x7cc4f0 : 0x1474b8);
            landMaterial.needsUpdate = true;
            arcMaterial.color.set(dark ? sky : 0x1474b8);
            dotMaterial.color.set(dark ? 0xffffff : 0x0f2147);
            homeMaterial.color.set(dark ? 0x7ff0dc : 0x0f9f8f);
            pulses.forEach(function (p) {
                var shades = p.tail.geometry.attributes.color, from = dark ? new THREE.Color(0x000000) : core;
                for (var i = 0; i < trailSteps; i++) {
                    var mixed = from.clone().lerp(tip, Math.pow(i / (trailSteps - 1), 2.2));
                    shades.setXYZ(i, mixed.r, mixed.g, mixed.b);
                }
                shades.needsUpdate = true;
                p.tail.material.blending = blending;
                p.tail.material.needsUpdate = true;
                p.head.material.map = dark ? glows.dark : glows.light;
                p.head.material.blending = blending;
                p.head.material.needsUpdate = true;
                p.ripple.material.color.set(dark ? 0x7ff0dc : 0x0f9f8f);
            });
        }
        applyTheme();
        new MutationObserver(applyTheme).observe(document.documentElement, { attributes: true, attributeFilter: ['data-bs-theme'] });

        function resize() {
            var box = canvas.parentElement.getBoundingClientRect();
            renderer.setSize(box.width, box.height, false);
            camera.aspect = box.width / Math.max(box.height, 1);
            camera.updateProjectionMatrix();
        }
        resize();
        if ('ResizeObserver' in window) new ResizeObserver(resize).observe(canvas.parentElement);

        var pointerX = 0, pointerY = 0, visible = true;
        window.addEventListener('pointermove', function (e) {
            pointerX = e.clientX / window.innerWidth - 0.5;
            pointerY = e.clientY / window.innerHeight - 0.5;
        }, { passive: true });
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) { visible = entries[0].isIntersecting; }).observe(canvas);
        }

        var lastTime = 0, step = 0;
        function frame() {
            var now = performance.now();
            step = reduced || !lastTime ? 0 : Math.min((now - lastTime) / 16.67, 3);
            lastTime = now;
            pulses.forEach(function (p) {
                p.clock += step;
                var cycle = p.clock % (p.duration + restFrames), t = Math.min(cycle / p.duration, 1);
                var eased = t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
                var tip = Math.round(eased * pathSteps), positions = p.tail.geometry.attributes.position;
                for (var i = 0; i < trailSteps; i++) {
                    var point = p.path[Math.max(0, tip - (trailSteps - 1 - i) * 2)];
                    positions.setXYZ(i, point.x, point.y, point.z);
                }
                positions.needsUpdate = true;
                p.head.position.copy(p.path[tip]);
                p.head.material.opacity = t < 1 ? 1 : 0;
                p.tail.visible = t < 1;
                var after = (cycle - p.duration) / restFrames;
                p.ripple.material.opacity = after > 0 ? (1 - after) * 0.9 : 0;
                p.ripple.scale.setScalar(after > 0 ? 1 + after * 3.2 : 1);
            });
            renderer.render(scene, camera);
        }
        function loop() {
            requestAnimationFrame(loop);
            if (!visible || document.hidden) return;
            globe.rotation.y += 0.0011;
            rings.rotation.z += 0.0008;
            scene.rotation.y += (pointerX * 0.35 - scene.rotation.y) * 0.04;
            scene.rotation.x += (pointerY * 0.2 - scene.rotation.x) * 0.04;
            frame();
        }
        if (reduced) frame(); else loop();
    }

    function initMotion() {
        if (!window.gsap || !window.ScrollTrigger || reduced) return;
        gsap.registerPlugin(ScrollTrigger);
        var line = document.querySelector('[data-process-line]');
        if (line) {
            gsap.fromTo(line, { scaleX: 0 }, { scaleX: 1, ease: 'none', scrollTrigger: { trigger: line.parentElement, start: 'top 80%', end: 'bottom 55%', scrub: true } });
        }
        gsap.utils.toArray('[data-process-step]').forEach(function (step, index) {
            gsap.from(step, { y: 28, opacity: 0, duration: 0.7, delay: index * 0.1, ease: 'power3.out', scrollTrigger: { trigger: step, start: 'top 88%', once: true } });
        });
        var glow = document.querySelector('.hero-visual-3d');
        if (glow) {
            gsap.to(glow, { yPercent: 12, ease: 'none', scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true } });
        }
    }

    // Cards lean towards the pointer
    function initTilt() {
        if (reduced || !window.matchMedia('(hover: hover)').matches) return;
        document.querySelectorAll('.bento-card').forEach(function (card) {
            card.addEventListener('pointermove', function (e) {
                var box = card.getBoundingClientRect();
                var x = (e.clientX - box.left) / box.width - 0.5, y = (e.clientY - box.top) / box.height - 0.5;
                card.style.transform = 'perspective(900px) rotateX(' + (-y * 5) + 'deg) rotateY(' + (x * 5) + 'deg) translateY(-3px)';
            });
            card.addEventListener('pointerleave', function () { card.style.transform = ''; });
        });
    }

    function start() {
        initGlobe();
        initMotion();
        initTilt();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
