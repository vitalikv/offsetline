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
			scene.add(helper);

			var contour1 = [
			  new THREE.Vector2(0, 0),
			  new THREE.Vector2(4, 4),
			  new THREE.Vector2(8, 4),
			  new THREE.Vector2(8, 0),
			  new THREE.Vector2(4, -4),
			  new THREE.Vector2(4, 0)
			];

			var color = 0xff0000;
			
			for (let i = 0; i < 6; i++){
				
			  let points = OffsetContour(i * -0.5, contour1);
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
			
			
		  function OffsetContour(offset, contour) {
			
			let result = [];
			
			offset = new THREE.BufferAttribute(new Float32Array([offset, 0, 0]), 3);
			console.log("offset", offset);
			
			for (let i = 0; i < contour.length; i++) {
			  let v1 = new THREE.Vector2().subVectors(contour[i - 1 < 0 ? contour.length - 1 : i - 1], contour[i]);
			  let v2 = new THREE.Vector2().subVectors(contour[i + 1 == contour.length ? 0 : i + 1], contour[i]);
			  let angle = v2.angle() - v1.angle();
			  let halfAngle = angle * 0.5;
					
			  let hA = halfAngle;
			  let tA = v2.angle() + Math.PI * 0.5;
			  
			  let shift = Math.tan(hA - Math.PI * 0.5);
			  let shiftMatrix = new THREE.Matrix4().set(
					 1, 0, 0, 0, 
				-shift, 1, 0, 0,
					 0, 0, 1, 0,
					 0, 0, 0, 1
			  );
					
			  
			  let tempAngle = tA;
			  let rotationMatrix = new THREE.Matrix4().set(
				Math.cos(tempAngle), -Math.sin(tempAngle), 0, 0,
				Math.sin(tempAngle),  Math.cos(tempAngle), 0, 0,
								  0,                    0, 1, 0,
								  0,                    0, 0, 1
			  );

			  let translationMatrix = new THREE.Matrix4().set(
				1, 0, 0, contour[i].x,
				0, 1, 0, contour[i].y,
				0, 0, 1, 0,
				0, 0, 0, 1,
			  );

			  let cloneOffset = offset.clone();
			  console.log("cloneOffset", cloneOffset);
				shiftMatrix.applyToBufferAttribute(cloneOffset);
			  rotationMatrix.applyToBufferAttribute(cloneOffset);
			  translationMatrix.applyToBufferAttribute(cloneOffset);

			  result.push(new THREE.Vector2(cloneOffset.getX(0), cloneOffset.getY(0)));
			}
			

			return result;
		  }			
	</script>
	
	</body>
</html>
