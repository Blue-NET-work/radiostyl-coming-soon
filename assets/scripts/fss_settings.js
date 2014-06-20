( function( $ ) {
    "use strict";
	
	var fcontainer, renderer, scene, light, geometry, material, mesh, now, start;
	
	/* ==========================================================================
    Flat Surface Shader
    ========================================================================== */
    fcontainer = document.getElementById('overlay');
    renderer = new FSS.CanvasRenderer();
    scene = new FSS.Scene();
    light = new FSS.Light('#000000', '#474e38');
    geometry = new FSS.Plane(fcontainer.offsetWidth, fcontainer.offsetHeight, 5, 3);
    material = new FSS.Material('#ffffff', '#ffffff');
    mesh = new FSS.Mesh(geometry, material);
    now = Date.now();
    start = Date.now();

    function initialise() {
        scene.add(mesh);
        scene.add(light);
        fcontainer.appendChild(renderer.element);
        window.addEventListener('resize', resize);
    }
    function resize() {
        renderer.setSize(fcontainer.offsetWidth, fcontainer.offsetHeight);
    }
    function animate() {
        now = Date.now() - start;
        light.setPosition(700 * Math.sin(now * 0.001), 350 * Math.cos(now * 0.0005), 100);
        renderer.render(scene);
        requestAnimationFrame(animate);
    }

    initialise();
    resize();
    animate();
	
} )( jQuery );