<!DOCTYPE html>
<html lang="en">
<head>
	<title>three.js webgl - geometry - spline extrusion</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<style>
		body {
			font-family: Monospace;
			background-color: #f0f0f0;
			margin: 0px;
			overflow: hidden;
		}

		#info {
			position: absolute;
			top: 0px;
			width: 100%;
			padding: 5px;
			font-family:Monospace;
			font-size:13px;
			text-align:center;
		}
	</style>
</head>
<body>

	<div id="container"></div>

	<script src="js/three.min.js"></script>
	<script src="js/OrbitControls.js"></script>
	<script src="offsetContour.js"></script>

	<script>

		var scene = new THREE.Scene();
		var camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 1, 1000);
		camera.position.set(0, 8, 1);
		var renderer = new THREE.WebGLRenderer({
		  antialias: false
		});
		renderer.setSize(window.innerWidth, window.innerHeight);
		document.body.appendChild(renderer.domElement);

		var controls = new THREE.OrbitControls(camera, renderer.domElement);

		var light = new THREE.DirectionalLight(0xffffff, 0.75);
		light.position.setScalar(10);
		scene.add(light);
		scene.add(new THREE.AmbientLight(0xffffff, 0.25));
		scene.background = new THREE.Color( 0xffffff );
		
		var helper = new THREE.GridHelper(8, 8);
		//scene.add(helper);
		
		
		var offset = 0.3;
		
		
	var geometry = new THREE.Geometry();
	geometry.vertices.push(new THREE.Vector3(0, 0, -4));
	geometry.vertices.push(new THREE.Vector3(0, 0, 0));
	var line = new THREE.Line( geometry, new THREE.LineBasicMaterial({color: 0xff0000, linewidth: 2 }) );
	scene.add( line );

	var geometry = new THREE.Geometry();
	geometry.vertices.push(new THREE.Vector3(offset, 0, -4));
	geometry.vertices.push(new THREE.Vector3(offset, 0, 0));
	var line = new THREE.Line( geometry, new THREE.LineBasicMaterial({color: 0x0422c9, linewidth: 2 }) );
	scene.add( line );		
		
		
		
		var x = 4;
		var y = 2.0;
		
		var contour1 = [
		  new THREE.Vector2(-x, y),
		  new THREE.Vector2(-x, -y),
		  new THREE.Vector2(x, -y),
		  new THREE.Vector2(x, y),
		];

		var dX = contour1[2].x - contour1[0].x;
		var dY = contour1[0].y - contour1[1].y;
		
		var dMin = (dX < dY) ? dX : dY;
		dMin /= 2;
		
		dMin += offset/2;
		var count = Math.round(dMin/offset);
		
		console.log(dMin, count);
		var color = 0xff0000;
		
		for (let i = 0; i < count; i++){
			
		  //let points = OffsetContour(i * (dMin/6), contour1);
		  let points = OffsetContour(i * offset, contour1);
		  console.log(points);
		  let geom = new THREE.BufferGeometry().setFromPoints(points);
		  geom.rotateX(-Math.PI * 0.5);
		  let line = new THREE.LineLoop(geom, new THREE.LineBasicMaterial({color: color }));
		  scene.add(line);

			color = (color == 0xff0000) ? 0x0422c9 : 0xff0000;
		}

		render();

		function render() {
		  requestAnimationFrame(render);
		  renderer.render(scene, camera);
		}
		
		
	
</script>

</body>
</html>
