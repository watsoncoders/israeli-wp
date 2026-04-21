

var hs = {

// Apply your own settings here, or override them in the html file.  
graphicsDir : 'slideshow_images/',
restoreCursor : "zoomout.cur", // necessary for preload
fullExpandIcon : 'fullexpand.gif',
expandSteps : 10, // number of steps in zoom. Each step lasts for duration/step milliseconds.
expandDuration : 250, // milliseconds
restoreSteps : 10,
restoreDuration : 250,
numberOfImagesToPreload : 5, // set to 0 for no preload
marginLeft : 10,
marginRight : 35, // leave room for scrollbars + outline
marginTop : 10,
marginBottom : 35, // leave room for scrollbars + outline
zIndexCounter : 1001, // adjust to other absolutely positioned elements
fullExpandTitle : 'Expand to actual size',
restoreTitle : '',
focusTitle : 'Forward',
loadingText : 'Loading...',
loadingTitle : 'Cancel',
loadingOpacity : 0.6,
showCredits : true, // you can set this to false if you want
creditsText : '',
creditsHref : '',
creditsTitle : '',


// These settings can also be overridden inline for each image
anchor : 'auto', // where the image expands from
align : 'auto', // position in the client (overrides anchor)
captionId : null,
slideshowGroup : '', // defines groups for next/previous links and keystrokes
enableKeyListener : true,
spaceForCaption : 30, // leaves space below images with captions
minWidth: 200,
minHeight: 200,
allowSizeReduction: true, // allow the image to reduce to fit client size. If false, this overrides minWidth and minHeight
outlineType : 'drop-shadow', // set null to disable outlines
wrapperClassName : null, // for enhanced css-control

		
// END OF YOUR SETTINGS


// declare internal properties
preloadTheseImages : new Array(),
continuePreloading: true,
expandedImagesCounter : 0,
expanders : new Array(),
overrides : new Array(
	'anchor',
	'align',
	'outlineType', 
	'spaceForCaption', 
	'wrapperClassName',
	'minWidth',
	'minHeight',
	'captionId',
	'allowSizeReduction',
	'slideshowGroup',
	'enableKeyListener'
),
overlays : new Array(),
toggleImagesGroup : null,

// drag functionality
ie : (document.all && !window.opera),
nn6 : document.getElementById && !document.all,
safari : navigator.userAgent.indexOf("Safari") != -1,
hasFocused : false,
isDrag : false,

$ : function (id) {
	return document.getElementById(id);
},

createElement : function (tag, attribs, styles, parent) {
	var el = document.createElement(tag);
	if (attribs) hs.setAttribs(el, attribs);
	if (styles) hs.setStyles(el, styles);
	if (parent) parent.appendChild(el);	
	return el;
},

setAttribs : function (el, attribs) {
	for (var x in attribs) {
		el[x] = attribs[x];
	}
},

setStyles : function (el, styles) {
	for (var x in styles) {
		el.style[x] = styles[x];
	}
},

ieVersion : function () {
	arr = navigator.appVersion.split("MSIE");
	return parseFloat(arr[1]);
},

//--- Find client width and height
clientInfo : function ()	{
	var iebody = (document.compatMode && document.compatMode != "BackCompat") 
		? document.documentElement : document.body;
	
	this.width = hs.ie ? iebody.clientWidth : self.innerWidth;
	this.height = hs.ie ? iebody.clientHeight : self.innerHeight;
	this.scrollLeft = hs.ie ? iebody.scrollLeft : pageXOffset;
	this.scrollTop = hs.ie ? iebody.scrollTop : pageYOffset;
} ,

//--- Finds the position of an element
position : function(el)	{ 
	var parent = el;
	var p = Array();
	p.x = parent.offsetLeft;
	p.y = parent.offsetTop;
	while (parent.offsetParent)	{
		parent = parent.offsetParent;
		p.x += parent.offsetLeft;
		p.y += parent.offsetTop;
	}
	return p;
}, 

expand : function(a, params, contentType) {
	if (1==1) {
		new HsExpander(a, params, contentType);
		return false;
		
	} else /* catch */ {
		return true;
	}
	
},

// hide elements that show through image
showHideElements : function (tagName, visibility, imgPos) {
	var els = document.getElementsByTagName(tagName);
	if (els) {			
		for (i = 0; i < els.length; i++) {
			if (els[i].nodeName == tagName) {   
				if (visibility == 'visible') { // only on last
					els[i].style.visibility = visibility;				
				} else {
					var elPos = hs.position(els[i]);
					elPos.w = els[i].offsetWidth;
					elPos.h = els[i].offsetHeight;
				
					var clearsX = (elPos.x + elPos.w < imgPos.x || elPos.x > imgPos.x + imgPos.w);
					var clearsY = (elPos.y + elPos.h < imgPos.y || elPos.y > imgPos.y + imgPos.h);
					if (!clearsX && !clearsY) { // element falls behind image
						els[i].style.visibility = visibility;	  
					}
				}   
			}
		}
	}
},

//--- Focus the topmost image after restore
focusTopmost : function() {
	var topZ = 0;
	var topmostKey = -1;
	for (i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i]) { // && i != hs.focusKey) {
			if (hs.expanders[i].wrapper.style.zIndex && hs.expanders[i].wrapper.style.zIndex > topZ) {
				topZ = hs.expanders[i].wrapper.style.zIndex;
				
				topmostKey = i;
			}
		}
	}
	if (topmostKey == -1) hs.focusKey = -1;
	else hs.expanders[topmostKey].focus();
}, 


closeId : function(elId) { // for text links
	for (i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i] && (hs.expanders[i].thumb.id == elId || hs.expanders[i].a.id == elId)) {
			hs.expanders[i].doClose();
			return;
		}
	}
},

close : function(el) {
	var key = hs.getWrapperKey(el);
	if (hs.expanders[key]) hs.expanders[key].doClose();
	return false;
},


toggleImages : function(closeId, expandEl) {
	if (closeId) hs.closeId(closeId);
	if (hs.ie) expandEl.href = expandEl.href.replace('about:(blank)?', ''); // mysterious IE thing
	hs.toggleImagesExpandEl = expandEl;
	return false;
},

getAdjacentAnchor : function(key, op) {
	var aAr = document.getElementsByTagName('A');
	var hsAr = new Array;	
	for (i = 0; i < aAr.length; i++) {
		if (hs.isHsAnchor(aAr[i])) {
			hsAr.push(aAr[i]);
		}
	}
	
	var activeI = -1;
	for (i = 0; i < hsAr.length; i++) {
		if (hs.expanders[key] && hsAr[i] == hs.expanders[key].a) {
			activeI = i;
			break;
		}
	}
	return hsAr[activeI + op];

},

previousOrNext : function (el, op) {
	if (typeof el == 'object') var activeKey = hs.getWrapperKey(el);
	else if (typeof el == 'number') var activeKey = el;
	if (hs.expanders[activeKey]) {
		hs.toggleImagesExpandEl = hs.getAdjacentAnchor(activeKey, op);
		hs.toggleImagesGroup = hs.expanders[activeKey].slideshowGroup;
		hs.expanders[activeKey].doClose();
	}
	
	return false;
},

previous : function (el) {
	return hs.previousOrNext(el, -1);
},

next : function (el) {
	return hs.previousOrNext(el, 1);	
},

keyHandler : function(e) {
	if (!e) e = window.event;
	if (!e.target) e.target = e.srcElement; // ie
	if (e.target.form) return; // form element has focus
	
	var op = null;
	switch (e.keyCode) {
		case 34: // Page Down
		case 39: // Arrow right
		case 40: // Arrow left
			op = 1;
			break;
		case 33: // Page Up
		case 37: // Arrow left
		case 38: // Arrow down
			op = -1;
	}
	//alert(hs.expanders[hs.focusKey].enableKeyListener);
	if (op != null) {
		document.onkeydown = null;
		if (hs.expanders[hs.focusKey] && hs.expanders[hs.focusKey].enableKeyListener == false) return true;
		return hs.previousOrNext(hs.focusKey, op);
	}
	else return true;
},

registerOverlay : function (overlay) {
	hs.overlays.push(overlay);
},

getWrapperKey : function (el) {
	var key = -1;
	while (el.parentNode)	{
		el = el.parentNode;
		if (el.id && el.id.match(/^highslide-wrapper-[0-9]+$/)) {
			key = el.id.replace(/^highslide-wrapper-([0-9]+)$/, "$1");
			break;
		}
	}
	return key;
},

onEndClose : function () {
	if (hs.hideIframes || hs.hideSelects) {
		var remaining = -1;
		for (i = 0; i < hs.expanders.length; i++) {
			if (hs.expanders[i]) remaining++;
		}
		if (remaining <= 0) {
			if (hs.hideSelects) hs.showHideElements('SELECT', 'visible');
			if (hs.hideIframes) hs.showHideElements('IFRAME', 'visible');		   
		}
	}
	
	if (hs.toggleImagesExpandEl) { 
		hs.toggleImagesExpandEl.onclick();
		hs.toggleImagesExpandEl = null;
	} else {
		for (i = 0; i < hs.expanders.length; i++) {
			if (hs.expanders[i] && hs.expanders[i].isExpanded) hs.focusTopmost();
		}		
	}
},

mouseDownHandler : function(e) 
{
	if (!e) e = window.event;
	if (e.button > 1) return true;
	if (!e.target) e.target = e.srcElement;
	
	var fobj = e.target;
	// loop out
	while (!fobj.tagName.match(/(HTML|BODY)/)	&& !fobj.className.match(/highslide-(image|move|html)/))
	{
		fobj = hs.nn6 ? fobj.parentNode : fobj.parentElement;
	}
	if (fobj.tagName.match(/(HTML|BODY)/)) return;

	hs.dragKey = hs.getWrapperKey(fobj);

	if (fobj.className.match(/highslide-(image|move)/)) // drag or focus
	{
		hs.isDrag = true;
		hs.dragObj = hs.expanders[hs.dragKey].content;

		if (fobj.className.match('highslide-image')) hs.dragObj.style.cursor = 'move';
		tx = parseInt(hs.expanders[hs.dragKey].wrapper.style.left);
		ty = parseInt(hs.expanders[hs.dragKey].wrapper.style.top);
		
		hs.leftBeforeDrag = tx;
		hs.topBeforeDrag = ty;
		
		hs.dragX = hs.nn6 ? e.clientX : event.clientX;
		hs.dragY = hs.nn6 ? e.clientY : event.clientY;
		document.onmousemove = hs.mouseMoveHandler;
		
		if (hs.dragObj.className.match(/highslide-(image|html)-blur/)) {
			hs.expanders[hs.dragKey].focus();
			hs.hasFocused = true;
		}
		return false;
	}
	else if (fobj.className.match(/highslide-html/)) { // just focus
		hs.expanders[hs.dragKey].focus();
		hs.hasFocused = false; // why??
	}
},

mouseMoveHandler : function(e)
{
	if (hs.isDrag) {
		if (!hs.expanders[hs.dragKey] || !hs.expanders[hs.dragKey].wrapper) return;
		var wrapper = hs.expanders[hs.dragKey].wrapper;
		
		var left = hs.nn6 ? tx + e.clientX - hs.dragX : tx + event.clientX - hs.dragX;
		wrapper.style.left = left +'px';
		var top = hs.nn6 ? ty + e.clientY - hs.dragY : ty + event.clientY - hs.dragY;
		wrapper.style.top  = top +'px';
		
		return false;
	}
}, 

mouseUpHandler : function(e) {
	if (!e) e = window.event;
	if (e.button > 1) return true;
	if (!e.target) e.target = e.srcElement;
	
	hs.isDrag = false;
	var fobj	   = e.target;
	
	while (!fobj.tagName.match(/(HTML|BODY)/) && !fobj.className.match(/highslide-(image|move)/))
	{
		fobj = fobj.parentNode;
	}
	if (fobj.className.match(/highslide-(image|move)/)) {
		if (fobj.className.match('highslide-image')) fobj.style.cursor = hs.styleRestoreCursor;
		var left = parseInt(fobj.parentNode.style.left);
		var top = parseInt(fobj.parentNode.style.top);
		if (left == hs.leftBeforeDrag && top == hs.topBeforeDrag && !hs.hasFocused) {
			hs.expanders[hs.dragKey].doClose();
		} else if (!hs.hasFocused) {
			hs.expanders[hs.dragKey].onEndMove();
		}
		hs.hasFocused = false;
	
	} else if (fobj.className.match('highslide-image-blur')) {
		fobj.style.cursor = hs.styleRestoreCursor;		
	}
},

isHsAnchor : function (a) {
	return (a.className && (a.className.match("highslide$") || a.className.match("highslide ")));
},

preloadFullImage : function (i) {
	if (hs.continuePreloading && hs.preloadTheseImages[i] && hs.preloadTheseImages[i] != 'undefined') {
		var img = document.createElement('img');
		img.onload = function() { hs.preloadFullImage(i + 1); }
		img.src = hs.preloadTheseImages[i];
	}
},

preloadImages : function (number) {
	if (number) this.numberOfImagesToPreload = number;
	
	var j = 0;
	
	var aTags = document.getElementsByTagName('A');
	for (i = 0; i < aTags.length; i++) {
		a = aTags[i];
		if (hs.isHsAnchor(a)) {
			if (j < this.numberOfImagesToPreload) {
				hs.preloadTheseImages[j] = a.rel || a.href;
				j++;
			}
		}
	}
	
	hs.preloadFullImage(0); // starts recursive process
	
	// preload cursor
	var cur = document.createElement('img');
	cur.src = hs.graphicsDir + hs.restoreCursor;
	
	// preload outlines
	if (hs.outlineType) {
		for (i = 1; i <= 8; i++) {
			var img = document.createElement('img');
			img.src = hs.graphicsDir + "outlines/"+ hs.outlineType +"/"+ i +".png";
		}
	}	
}

} // end hs object

//-----------------------------------------------------------------------------
// The expander object
HsExpander = function(a, params, contentType) {
	if (1==1) {
		
		hs.continuePreloading = false;
		hs.container = hs.$('highslide-container');
		
		if (params && params.thumbnailId) {
			var el = hs.$(params.thumbnailId);
		
		} else { // first img within anchor
			for (i = 0; i < a.childNodes.length; i++) {
				if (a.childNodes[i].tagName && a.childNodes[i].tagName == 'IMG') {
					var el = a.childNodes[i];
					break;
				}			
			}
		}
		if (!el) el = a;
		
		// cancel other instances
		for (i = 0; i < hs.expanders.length; i++) {
			if (hs.expanders[i] && hs.expanders[i].thumb != el && !hs.expanders[i].onLoadStarted) {
				hs.expanders[i].cancelLoading();
			}
		}
		
		// check if already open
		for (i = 0; i < hs.expanders.length; i++) {
			if (hs.expanders[i] && hs.expanders[i].thumb == el) {
				hs.expanders[i].focus();
				return false;
			}
		}
		
		this.key = hs.expandedImagesCounter++;
		hs.expanders[this.key] = this;
		if (contentType == 'html') {
			this.isHtml = true;
			this.contentType = 'html';
		} else {
			this.isImage = true;
			this.contentType = 'image';
		}
		this.a = a;
		
		
		// override inline parameters
		for (i = 0; i < hs.overrides.length; i++) {
			var name = hs.overrides[i];
			if (params && params[name] != undefined) this[name] = params[name];
			else this[name] = hs[name];
		}
		
		// check slideshowGroup
		if (hs.toggleImagesGroup != null && hs.toggleImagesGroup != this.slideshowGroup) {
			hs.toggleImagesGroup = null;
			hs.expanders[this.key] = null;
			return;
		}
		
		
		this.thumbsUserSetId = el.id || a.id;
		this.thumb = el;		
		
		this.overlays = new Array();

		var pos = hs.position(el); 
				
		// instanciate the wrapper
		this.wrapper = hs.createElement(
			'div',
			{
				id: 'highslide-wrapper-'+ this.key,
				className: this.wrapperClassName
			},
			{
				visibility: 'hidden',
				position: 'absolute',
				zIndex: hs.zIndexCounter++
			}
		)			
		
		// store properties of the thumbnail
		this.thumbWidth = el.width ? el.width : el.offsetWidth;		
		this.thumbHeight = el.height ? el.height : el.offsetHeight;
		this.thumbLeft = pos.x;
		this.thumbTop = pos.y;
		this.thumbClass = el.className;
		
		// thumb borders
		this.thumbOffsetBorderW = (this.thumb.offsetWidth - this.thumbWidth) / 2;
		this.thumbOffsetBorderH = (this.thumb.offsetHeight - this.thumbHeight) / 2;
		
		if (this.isImage) this.imageCreate();
	
		return false;
	
	} else /* catch */ {
		return true;
	}
	
}

HsExpander.prototype.displayLoading = function() {
	if (this.onLoadStarted) return;
		
	this.originalCursor = this.a.style.cursor;
	this.a.style.cursor = 'wait';
	
	this.loading = hs.createElement('a',
		{
			className: 'highslide-loading',
			title: hs.loadingTitle,
			href: 'javascript:hs.expanders['+ this.key +'].cancelLoading()',
			innerHTML: hs.loadingText			
		},
		{
			position: 'absolute',
			visibility: 'hidden'
		}, hs.container);
		
	if (hs.ie) this.loading.style.filter = 'alpha(opacity='+ (100*hs.loadingOpacity) +')';
	else this.loading.style.opacity = hs.loadingOpacity;
	
	this.loading.style.left = (this.thumbLeft + this.thumbOffsetBorderW 
		+ (this.thumbWidth - this.loading.offsetWidth) / 2) +'px';
	this.loading.style.top = (this.thumbTop 
		+ (this.thumbHeight - this.loading.offsetHeight) / 2) +'px';
	setTimeout(
		"if (hs.expanders["+ this.key +"] && hs.expanders["+ this.key +"].loading) "
		+ "hs.expanders["+ this.key +"].loading.style.visibility = 'visible';", 
		100
	);
}

HsExpander.prototype.imageCreate = function() {
	var img = document.createElement('img');
	var key = this.key;

	var img = document.createElement('img');
    this.content = img;
    img.onload = function () { if (hs.expanders[key]) hs.expanders[key].onLoad();  }
    img.className = 'highslide-image '+ this.thumbClass;
    img.style.visibility = 'hidden' // prevent flickering in IE
    img.style.display = 'block';
	img.style.position = 'absolute';
    img.style.zIndex = 3;
	//img.id = 'expanded-'+ this.key;
    img.title = hs.restoreTitle;
    img.onmouseover = function () { 
    	if (hs.expanders[key]) hs.expanders[key].onMouseOver(); 
    }
    img.onmouseout = function (e) { 
    	var rel = e ? e.relatedTarget : event.toElement;
		if (hs.expanders[key]) hs.expanders[key].onMouseOut(rel);
	}
    if (hs.safari) hs.container.appendChild(img);
	img.src = this.a.rel || this.a.href;
	
	this.displayLoading();
	
	        
}

HsExpander.prototype.onLoad = function() {	
	if (1==1) { 
		if (!this.content) return;
		
		// prevent looping on certain Gecko engines:
		if (this.onLoadStarted) return;
		else this.onLoadStarted = true;
		
			   
		if (this.isImage) {
			if (this.loading) {
				hs.container.removeChild(this.loading);
				this.loading = null;
			}
			this.a.style.cursor = this.originalCursor || '';
			
			this.newWidth = this.content.width;
			this.newHeight = this.content.height;
			this.fullExpandWidth = this.newWidth;
			this.fullExpandHeight = this.newHeight;
			
			this.content.width = this.thumbWidth;
			this.content.height = this.thumbHeight;
		}
	
		// identify caption div
		var modMarginBottom = hs.marginBottom;
		if (this.captionId && hs.$(this.captionId)) {
			this.caption = hs.$(this.captionId).cloneNode(true);
		} else if (this.thumbsUserSetId && hs.$('caption-for-'+ this.thumbsUserSetId)) {
			this.caption = hs.$('caption-for-'+ this.thumbsUserSetId).cloneNode(true);
		}
		if (this.caption) modMarginBottom += this.spaceForCaption;
		
		
		this.wrapper.appendChild(this.content);
		if (this.caption) this.wrapper.appendChild(this.caption);
		this.content.style.position = 'relative'; // Saf
		this.wrapper.style.left = this.thumbLeft +'px';
		this.wrapper.style.top = this.thumbTop +'px';
		hs.container.appendChild(this.wrapper);
		
		// correct for borders
		this.offsetBorderW = (this.wrapper.offsetWidth - this.thumbWidth) / 2;
		this.offsetBorderH = (this.wrapper.offsetHeight - this.thumbHeight) / 2;
		var modMarginRight = hs.marginRight + 2 * this.offsetBorderW;
		modMarginBottom += 2 * this.offsetBorderH;
		
		var ratio = this.newWidth / this.newHeight;
		var minWidth = this.allowSizeReduction ? this.minWidth : this.newWidth;
		var minHeight = this.allowSizeReduction ? this.minHeight : this.newHeight;
		
		var justify = { x: 'auto', y: 'auto' };
		if (this.align == 'center') {
			justify.x = 'center';
			justify.y = 'center';
		} else {
			if (this.anchor.match(/^top/)) justify.y = null;
			if (this.anchor.match(/right$/)) justify.x = 'max';
			if (this.anchor.match(/^bottom/)) justify.y = 'max';
			if (this.anchor.match(/left$/)) justify.x = null;
		}
		
		client = new hs.clientInfo();
		
		
		// justify
		this.x = { 
			min: parseInt(this.thumbLeft) - this.offsetBorderW + this.thumbOffsetBorderW,
			span: this.newWidth,
			minSpan: this.newWidth < minWidth ? this.newWidth : minWidth,
			justify: justify.x, 
			marginMin: hs.marginLeft, 
			marginMax: modMarginRight,
			scroll: client.scrollLeft,
			clientSpan: client.width,
			thumbSpan: this.thumbWidth
		};
		var oldRight = this.x.min + parseInt(this.thumbWidth);
		this.x = this.justify(this.x);

		this.y = { 
			min: parseInt(this.thumbTop) - this.offsetBorderH + this.thumbOffsetBorderH,
			span: this.newHeight,
			minSpan: this.newHeight < minHeight ? this.newHeight : minHeight,
			justify: justify.y, 
			marginMin: hs.marginTop, 
			marginMax: modMarginBottom, 
			scroll: client.scrollTop,
			clientSpan: client.height,
			thumbSpan: this.thumbHeight
		};
		var oldBottom = this.y.min + parseInt(this.thumbHeight);        
		this.y = this.justify(this.y);

		if (this.isHtml) this.htmlSizeOperations();


		// correct ratio
		if (this.isImage) this.correctRatio(ratio);

		var x = this.x;
		var y = this.y;


		// Selectbox bug
		var imgPos = {x: x.min - 20, y: y.min - 20, w: x.span + 40, h: y.span + 40 + this.spaceForCaption}
		hs.hideSelects = (hs.ie && hs.ieVersion() < 7);
		if (hs.hideSelects) hs.showHideElements('SELECT', 'hidden', imgPos);
		// Iframes bug
		hs.hideIframes = (window.opera || navigator.vendor == 'KDE' || (hs.ie && hs.ieVersion() < 5.5));
		if (hs.hideIframes) hs.showHideElements('IFRAME', 'hidden', imgPos);
		
		// Apply size change
		this.changeSize(
			this.thumbLeft + this.thumbOffsetBorderW - this.offsetBorderW,
			this.thumbTop + this.thumbOffsetBorderH - this.offsetBorderH,
			this.thumbWidth,
			this.thumbHeight,
			x.min,
			y.min,
			x.span,
			y.span, 
			hs.expandDuration,
			hs.expandSteps
		);			
		
		setTimeout(
			"if (hs.expanders["+ this.key +"])"
			+ "hs.expanders["+ this.key +"].onExpanded()",
			hs.expandDuration
		);

	} else /* catch */ {
		if (hs.expanders[this.key] && hs.expanders[this.key].a) window.location.href = hs.expanders[this.key].a.href;
	}
}

HsExpander.prototype.changeSize = function(x1, y1, w1, h1, x2, y2, w2, h2, dur, steps) {
	dW = (w2 - w1) / steps;
	dH = (h2 - h1) / steps;
	dX = (x2 - x1) / steps;
	dY = (y2 - y1) / steps;
	
	for (i = 1; i < hs.expandSteps; i++) {
		w1 += dW;
		h1 += dH;
		x1 += dX;
		y1 += dY;
		
		setTimeout(
			"if (hs.expanders["+ this.key +"]) "
			+ "hs.expanders["+ this.key +"]."+ this.contentType +"SetSize("
			+ w1 +", "+ h1 +", "+ x1 +", "+ y1 +")", 
			Math.round(i * (dur / steps))
		);
	}
}

HsExpander.prototype.imageSetSize = function (width, height, left, top) {
	if (1==1) {
		this.content.width = width;
		this.content.height = height;
		
		hs.setStyles ( this.wrapper,
			{
				'visibility': 'visible',
				'left': left +'px',
				'top': top +'px'
			}
		)
		this.content.style.visibility = 'visible';
		if (this.thumb.tagName == 'IMG') this.thumb.style.visibility = 'hidden';
		
	} else /* catch */ {
		window.location.href = hs.expanders[this.key].a.href;
	}
}

HsExpander.prototype.onExpanded = function() {
	this[this.contentType +'SetSize'](this.x.span, this.y.span, this.x.min, this.y.min);
	this.isExpanded = true;
	this.focus();
	this.createCustomOverlays();
	if (hs.showCredits) this.writeCredits();
	
	if (this.caption) this.writeCaption();
	else if (this.outlineType) this.writeOutline();
	
	if (this.fullExpandWidth > this.x.span) this.createFullExpand();
	
	if (!this.caption && !this.outlineType) this.onDisplayFinished();
}

HsExpander.prototype.onDisplayFinished = function() {
	// preload next
	var nextA = hs.getAdjacentAnchor(this.key, 1);
	if (nextA) {
		var img = document.createElement('img');
		img.src = nextA.rel || nextA.href;
	}
}

HsExpander.prototype.justify = function (p) {
	if (p.justify == 'auto' || p.justify == 'center') {
		var hasMovedMin = false;
		var allowReduce = true;
		// calculate p.min
		if (p.justify == 'center') p.min = Math.round(p.scroll + (p.clientSpan - p.span - p.marginMax) / 2);
		else p.min = Math.round(p.min - ((p.span - p.thumbSpan) / 2)); // auto
		if (p.min < p.scroll + p.marginMin) {
			p.min = p.scroll + p.marginMin;
			hasMovedMin = true;		
		}
		
		if (p.span < p.minSpan) {
			p.span = p.minSpan;
			allowReduce = false;
		}
		// calculate right/newWidth
		if (p.min + p.span > p.scroll + p.clientSpan - p.marginMax) {
			if (hasMovedMin && allowReduce) p.span = p.clientSpan - p.marginMin - p.marginMax; // can't expand more
			else if (p.span < p.clientSpan - p.marginMin - p.marginMax) { // move newTop up
				p.min = p.scroll + p.clientSpan - p.span - p.marginMin - p.marginMax;
			} else { // image larger than client
				p.min = p.scroll + p.marginMin;
				if (allowReduce) p.span = p.clientSpan - p.marginMin - p.marginMax;
			}
			
		}
		
		if (p.span < p.minSpan) {
			p.span = p.minSpan;
			allowReduce = false;
		}
		
	} else if (p.justify == 'max') {
		p.min = Math.floor(p.min - p.span + p.thumbSpan);
	}
	
	if (p.min < p.marginMin) {
		tmpMin = p.min;
		p.min = p.marginMin; 
		if (allowReduce) p.span = p.span - (p.min - tmpMin);
	}
	return p;
}

HsExpander.prototype.correctRatio = function(ratio) {
	var x = this.x;
	var y = this.y;
	var changed = false;
	if (x.span / y.span > ratio) { // width greater
		var tmpWidth = x.span;
		x.span = y.span * ratio;
		if (x.span < x.minSpan) { // below minWidth
			x.span = x.minSpan;	
			y.span = x.span / ratio;
		}
		changed = true;
	
	} else if (x.span / y.span < ratio) { // height greater
		var tmpHeight = y.span;
		y.span = x.span / ratio;
		changed = true;
	}
	
	if (changed) {
		x.min = parseInt(this.thumbLeft) - this.offsetBorderW + this.thumbOffsetBorderW;
		x.minSpan = x.span;
		this.x = this.justify(x);
		
		y.min = parseInt(this.thumbTop) - this.offsetBorderH + this.thumbOffsetBorderH;
		y.minSpan = y.span;
		this.y = this.justify(y);
	}
}

HsExpander.prototype.cancelLoading = function() {
	this.a.style.cursor = this.originalCursor;
	
	if (this.loading) {
		hs.container.removeChild(this.loading);
		this.loading = null;
	}
		
	hs.expanders[this.key] = null;
}

HsExpander.prototype.writeCredits = function () {
	var credits = hs.createElement('a',
		{
			href: hs.creditsHref,
			className: 'highslide-credits',
			innerHTML: hs.creditsText,
			title: hs.creditsTitle
		}
	)
	this.createOverlay(credits, 'top left');

}

HsExpander.prototype.writeCaption = function() {
	try {
		this.wrapper.style.width = this.wrapper.offsetWidth +'px';	
		this.caption.style.visibility = 'hidden';
		this.caption.style.position = 'relative';
		if (hs.ie) this.caption.style.zoom = 1;  
		this.caption.className += ' highslide-display-block'; // have to use className due to Opera
		
		var capHeight = this.caption.offsetHeight;
		var slideHeight = (capHeight < this.content.height) ? capHeight : this.content.height;
		this.caption.style.marginTop = '-'+ slideHeight +'px';
		
		this.caption.style.zIndex = 2;
		
		var step = 1;
		if (slideHeight > 400) step = 4;
		else if (slideHeight > 200) step = 2;
		else if (slideHeight > 100) step = 1;
		

		setTimeout("if (hs.expanders["+ this.key +"] && hs.expanders["+ this.key +"].caption) "
				+ "hs.expanders["+ this.key +"].caption.style.visibility = 'visible'", 10); // flickering in Gecko
		var t = 0;
		
		for (marginTop = -slideHeight; marginTop <= 0; marginTop += step, t += 10) {
			var eval = "if (hs.expanders["+ this.key +"] && hs.expanders["+ this.key +"].caption) { "
				+ "hs.expanders["+ this.key +"].caption.style.marginTop = '"+ marginTop +"px';"
			if (marginTop == 0) eval += 'hs.expanders['+ this.key +'].writeOutline();';
			eval += "}";
			
			setTimeout (eval, t);
		}
		
	} catch (e) {}	
}

HsExpander.prototype.writeOutline = function() {
	if (!this.outlineType) {
		this.onDisplayFinished();
		return;	
	}
	this.outline = new Array();
	var v = hs.ieVersion();
	
	hs.hasAlphaImageLoader = (v >= 5.5 && document.body.filters);
	hs.hasPngSupport = !document.body.filters;
	
	this.preloadOutlineElement(1); // start recursive process
}

HsExpander.prototype.preloadOutlineElement = function(i) {
	if (!hs.hasAlphaImageLoader && !hs.hasPngSupport) return;
	
	if (this.outline[i] && this.outline[i].onload) { // Gecko multiple onloads bug
		this.outline[i].onload = null;
		return;
	}
	
	var src = hs.graphicsDir + "outlines/"+ this.outlineType +"/"+ i +".png";
	
	if (hs.hasAlphaImageLoader) {
		
		this.outline[i] = document.createElement('div');
		this.outline[i].style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader("
			+ "enabled=true, sizingMethod=scale src='"+ src + "') ";
	} 
		
	var img = document.createElement('img'); // for onload trigger
	if (hs.hasPngSupport) {
		this.outline[i] = img;
	}
	
	// common properties
	this.outline[i].style.position = 'absolute';
	var dim = (i % 2 == 1) ? '10px' : '20px';
	this.outline[i].style.height = dim;
	this.outline[i].style.width = dim;	
	if (hs.ie) this.outline[i].style.lineHeight = dim;
	
	var pThis = this;
	if (i < 8) img.onload = function() { pThis.preloadOutlineElement(i + 1); };				
	else img.onload = function() { pThis.displayOutline(); };
	if (hs.safari) {
		this.outline[i].style.left = '10px';
		this.outline[i].style.top = '10px';
		hs.container.appendChild(img);
	}
	img.src = src;
}

HsExpander.prototype.displayOutline = function() {
	this.repositionOutline(12);
	for (i = 1; i <= 8; i++) {
		this.wrapper.appendChild(this.outline[i]);
	}
	this.hasOutline = true;

	for (i = 10, t = 0; i >= 0; i--, t += 50) {
		setTimeout(
			'if (hs.expanders['+ this.key +']) hs.expanders['+ this.key +'].repositionOutline('+ i +')',
			t
		);
	}
}

HsExpander.prototype.repositionOutline = function(offset) {
	if (this.isClosing) return;
	
	var w = this.wrapper.offsetWidth;
	var h = this.wrapper.offsetHeight;

	var fix = Array (
		Array (Array (1, 5), 'width', (w - (2 * offset) - 20) +'px'),
		Array (Array (1, 5), 'left', (10 + offset) +'px'),
		Array (Array (1, 2, 8), 'top', (-10 + offset) + 'px'),
		Array (Array (2, 4), 'left', (w - 10 - offset) +'px'),
		Array (Array (3, 3), 'left', (w - offset) +'px'),
		Array (Array (3, 7), 'top', (10 + offset) +'px'),
		Array (Array (3, 7), 'height', (h - (2 * offset) - 20) +'px'),
		Array (Array (4, 6), 'top', (h - 10 - offset) +'px'),
		Array (Array (5, 5), 'top', (h - offset) + 'px'),
		Array (Array (6, 7, 8), 'left', (-10 + offset) +'px')
	);
	// strange khtml bug causes glitch in outline:
	if (navigator.vendor == 'KDE') {
		fix.push(Array(1, 5), 'height', ((offset % 2) + 10) +'px');
	}
	for (i = 0; i < fix.length; i++) {
		for (j = 0; j < fix[i][0].length; j++) {
			this.outline[fix[i][0][j]].style[fix[i][1]] = fix[i][2];
		}
	}
	
	if (offset == 0) this.onDisplayFinished();
}

HsExpander.prototype.focus = function() {
	// blur others
	for (i = 0; i < hs.expanders.length; i++) {
		if (hs.expanders[i] && i == hs.focusKey) {
			var blurExp = hs.expanders[i];
			blurExp.content.className += ' highslide-'+ blurExp.contentType +'-blur';
			if (blurExp.caption) {
				blurExp.caption.className += ' highslide-caption-blur';
			}
			if (blurExp.isImage) {
				blurExp.content.style.cursor = hs.ie ? 'hand' : 'pointer';
				blurExp.content.title = hs.focusTitle;	
			}
		}
	}
	
	// focus this
	this.wrapper.style.zIndex = hs.zIndexCounter++;
	
	this.content.className = 'highslide-'+ this.contentType;
	if (this.caption) {
		this.caption.className = this.caption.className.replace(' highslide-caption-blur', '');
	}
	
	if (this.isImage) {
		this.content.title = hs.restoreTitle;
		
		hs.styleRestoreCursor = window.opera ? 'pointer' : 'url('+ hs.graphicsDir + hs.restoreCursor +'), pointer';
		this.content.style.cursor = hs.styleRestoreCursor;
	}
	
	hs.focusKey = this.key;
	document.onkeydown = hs.keyHandler;
}

HsExpander.prototype.doClose = function() {
	document.onkeydown = null;
	if (1==1) {
		if (!hs.expanders[this.key]) return;
		var exp = hs.expanders[this.key];

		this.isClosing = true;
		
		// remove children
		var n = this.wrapper.childNodes.length;
		for (i = n - 1; i >= 0 ; i--) {
			var child = this.wrapper.childNodes[i];
			if (child != this.content) {
				this.wrapper.removeChild(this.wrapper.childNodes[i]);
			}
		}
		
		if (this.isHtml && this.scrollerDiv != 'scrollingContent') exp[this.scrollerDiv].style.overflow = 'hidden';

		hs.outlinePreloader = 0;
		this.wrapper.style.width = null;
		
		var width = (this.isImage) ? this.content.width : parseInt(this.content.style.width);
		var height = (this.isImage) ? this.content.height : parseInt(this.content.style.height);
		this.changeSize(
			parseInt(this.wrapper.style.left),
			parseInt(this.wrapper.style.top),
			width,
			height,
			this.thumbLeft,
			this.thumbTop,
			this.thumbWidth,
			this.thumbHeight, 
			hs.restoreDuration,
			hs.restoreSteps
		);			
		
		setTimeout('if (hs.expanders['+ this.key +']) hs.expanders['+ this.key +'].cleanUp()', hs.restoreDuration);
		setTimeout('hs.onEndClose()', hs.restoreDuration);
	
	} else /* catch */ {
		hs.expanders[this.key].thumb.style.visibility = 'visible';
		hs.expanders[this.key].wrapper.parentNode.removeChild(hs.expanders[this.key].wrapper);
	}
}

HsExpander.prototype.cleanUp = function () {
	this.thumb.style.visibility = 'visible';
	this.content.style.visibility = 'hidden';
	
	this.wrapper.parentNode.removeChild(this.wrapper);
	hs.expanders[this.key] = null;

}

HsExpander.prototype.createOverlay = function (el, position, hideOnMouseOut, opacity) {
	if (typeof el == 'string' && hs.$(el)) {
		el = hs.$(el).cloneNode(true);
		el.id = null;	
	}
	if (!el || typeof el == 'string' || !this.isImage) return;
	
	if (!position) var position = 'center center';
	
	var overlay = hs.createElement(
		'div',
		null,
		{
			'position' : 'absolute',
			'zIndex' : 3,
			'visibility': 'hidden'
		},
		this.wrapper
	);
	if (opacity && opacity < 1) {
		if (hs.ie) overlay.style.filter = 'alpha(opacity='+ (opacity * 100) +')';
		else overlay.style.opacity = opacity;
	}
	el.className += ' highslide-display-block';
	overlay.appendChild(el);
	
	if (hs.ie && this.isImage) { // strange bug sometimes makes values wrong in the first def.
		this.offsetBorderW = (this.wrapper.offsetWidth - this.content.width) / 2;
		this.offsetBorderH = (this.wrapper.offsetHeight - this.content.height) / 2;
	}
	var left = this.offsetBorderW;
	var top = this.offsetBorderH;
	
	if (position.match(/^bottom/)) top += this.content.height - overlay.offsetHeight;
	if (position.match(/^center/)) top += (this.content.height - overlay.offsetHeight) / 2;
	if (position.match(/right$/)) left += this.content.width - overlay.offsetWidth;
	if (position.match(/center$/)) left += (this.content.width - overlay.offsetWidth) / 2;
	overlay.style.left = left +'px';
	overlay.style.top = top +'px';
	
	if (this.mouseIsOver || !hideOnMouseOut) overlay.style.visibility = 'visible';
	if (hideOnMouseOut) overlay.setAttribute('hideOnMouseOut', true);
	
	this.overlays.push(overlay);
}

HsExpander.prototype.onMouseOver = function () {
	this.mouseIsOver = true;
	for (i = 0; i < this.overlays.length; i++) {
		this.overlays[i].style.visibility = 'visible';
	}
}

HsExpander.prototype.onMouseOut = function(rel) {
	this.mouseIsOver = false;
	var hideThese = new Array();
	for (i = 0; i < this.overlays.length; i++) {
		var node = rel;
		while (node && node.parentNode) {
			if (node == this.overlays[i]) return;
			node = node.parentNode;
		}
		if (this.overlays[i].getAttribute('hideOnMouseOut')) {
			hideThese.push(this.overlays[i]);
		}
	}
	for (i = 0; i < hideThese.length; i++) {		
		hideThese[i].style.visibility = 'hidden';
	}
}

HsExpander.prototype.createCustomOverlays = function() {
	for (i = 0; i < hs.overlays.length; i++) {
		var o = hs.overlays[i];
		if (o.thumbnailId == null || o.thumbnailId == this.thumbsUserSetId) {
			this.createOverlay(o.overlayId, o.position, o.hideOnMouseOut, o.opacity);
		}
	}
}
HsExpander.prototype.createFullExpand = function () {
	var a = hs.createElement(
		'a',
		{
			href: 'javascript:hs.expanders['+ this.key +'].doFullExpand();',
			title: hs.fullExpandTitle
		},
		{
			background: 'url('+ hs.graphicsDir + hs.fullExpandIcon+')',
			display: 'block',
			margin: '0 10px 10px 0',
			width: '45px',
			height: '44px'
		}
	)
	
	this.createOverlay(a, 'bottom right', true, 0.75);
	this.fullExpandIcon = a;
}

HsExpander.prototype.doFullExpand = function () {
	if (1==1) {
	
		var newLeft = parseInt(this.wrapper.style.left) - (this.fullExpandWidth - this.content.width) / 2;
		if (newLeft < hs.marginLeft) newLeft = hs.marginLeft;
		this.wrapper.style.left = newLeft +'px';
		
		var borderOffset = this.wrapper.offsetWidth - this.content.width;
		
		
		this.content.width = this.fullExpandWidth;
		this.content.height = this.fullExpandHeight;
		this.focus();
		
		this.fullExpandIcon.parentNode.removeChild(this.fullExpandIcon);
		
		this.wrapper.style.width = (this.content.width + borderOffset) +'px';
		
		if (this.outlineType) this.repositionOutline(0);
		
		this.onEndMove();
	
	} else /* catch */ {
		window.location.href = hs.expanders[key].content.src;
	}
}

// on end move and resize
HsExpander.prototype.onEndMove = function() {
	var imgPos = {
		x: parseInt(this.wrapper.style.left) - 20, 
		y: parseInt(this.wrapper.style.top) - 20, 
		w: this.content.width + 40, 
		h: this.content.height + 40 + this.spaceForCaption
	}		
	if (hs.hideSelects) hs.showHideElements('SELECT', 'hidden', imgPos);
	if (hs.hideIframes) hs.showHideElements('IFRAME', 'hidden', imgPos);
		
}

// set handlers

var oldonload = window.onload;

if (typeof window.onload != 'function') 
{
    window.onload = function () 
					{ document.onmousedown=hs.mouseDownHandler; 
					  document.onmouseup=hs.mouseUpHandler; 
					}
} 
else 
{
	window.onload = function() 
	{
	  	if (oldonload) 
		{
			oldonload();
  		}

		document.onmousedown=hs.mouseDownHandler; 
		document.onmouseup=hs.mouseUpHandler; 
	}
}


