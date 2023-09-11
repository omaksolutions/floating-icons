function randommaker(range) {
	rand = Math.floor(range*Math.random());
	return rand
}

function initicon() {
	if (opera) {
		marginbottom = jQuery('body').prop('scrollHeight');
		marginright = jQuery('body').prop('clientWidth')-15;
	}
	var iconsizerange = iconmaxsize - iconminsize;
	for (i=0;i<=iconmax;i++) {
		crds[i] = 0;
		lftrght[i] = Math.random()*15;
		x_mv[i] = 0.03 + Math.random()/10;
		icon[i]= jQuery("#s"+i);
		icon[i].css("font-family", icontype[randommaker(icontype.length)]);
		icon[i].size = randommaker(iconsizerange) + iconminsize;
		icon[i].css('font-size', icon[i].size+'px') ;
		icon[i].css('color', iconcolor[randommaker(iconcolor.length)]);
		icon[i].css('z-index', 1000);
		icon[i].sink = sinkspeed * icon[i].size/5;
		if (iconingzone==1) {
			icon[i].posx=randommaker(marginright-icon[i].size);
		}
		if(iconingzone == 2) {
			marginright = jQuery(selector).prop('clientWidth');
			marginbottom = jQuery(selector).prop('scrollHeight')-50;
			icon[i].posx = randommaker(marginright-icon[i].size);
		}
		icon[i].posy = randommaker(2*marginbottom-marginbottom-2*icon[i].size);
		icon[i].css('left', icon[i].posx+'px');
		icon[i].css('top', icon[i].posy+'px');
	}
	moveicon();
}

function moveicon() {
	for (i=0;i<=iconmax;i++) {
		crds[i] += x_mv[i];
		icon[i].posy += icon[i].sink;
		icon[i].css('left', icon[i].posx+lftrght[i]*Math.sin(crds[i])+'px');
		icon[i].css('top', icon[i].posy+'px');

		//console.log(icon[i].css('top'));

		if (icon[i].posy>=marginbottom-2*icon[i].size || parseInt(icon[i].posx+lftrght[i]*Math.sin(crds[i])+'px')>(marginright-3*lftrght[i])) {
			if (iconingzone==1) {
				icon[i].posx=randommaker(marginright-icon[i].size);
			}
			if (iconingzone==2) {
				icon[i].posx=randommaker(marginright-icon[i].size);
			}
			icon[i].posy = 0;
		}
	}
	var timer = setTimeout("moveicon()",50);
}



	