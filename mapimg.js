switch (phantom.state) {	
	case 'hoge':
		break;
		
	case 'waiting':
		var wait = 0;
		var id = setInterval(function () {
			console.log([window.auau, wait]);
			if (window.auau) {
				clearInterval(id);
				var count = 10;
				id = setInterval(function () {
					if (--count == 0) {
						phantom.render(phantom.args[2]);
						phantom.exit();
					}
				}, 100);
			} else {
				if (++wait == 100) {
					phantom.exit(1);
				}
			}
		}, 100);
		phantom.state = 'hoge';
		break;
		
	case 'prepare':
		if (phantom.args.length < 3) {
			console.log('please specify initiol latlng.');
			phantom.exit(1);
		} else {
			//window.initialize(38.259638, 140.879902);
			window.initialize(phantom.args[0], phantom.args[1]);
			phantom.state = 'waiting';
		}
		break;
		
	default: // init
		phantom.state = 'prepare';
		phantom.viewportSize = {width:200, height:200};
		phantom.open('mapimg.html');
}
