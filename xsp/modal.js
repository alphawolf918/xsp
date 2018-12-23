(function($){
	$.fn.XspModal = function(prop){
		var options = $.extend({
			height : "250",
			width : "500",
			title : "Dialog Box",
			description : "Modal Message",
			top : "20%",
			left : "30%",
		},prop);
		return this.click(function(e){
			add_block_page();
			add_popup_box();
			add_styles();
			$('.dialog').fadeIn();
		});
		function add_styles(){
			var pageHeight = $(document).height();
			var pageWidth = $(window).width();
			$('.dialog').css({ 
				'left' : options.left,
				'top' : options.top,
				'display':'none',
				'height' : options.height + 'px',
				'width' : options.width + 'px',
				'border' : '1px solid #fff',
				'box-shadow' : '0px 2px 7px #292929',
				'-moz-box-shadow' : '0px 2px 7px #292929',
				'-webkit-box-shadow' : '0px 2px 7px #292929',
				'border-radius' : '10px',
				'background' : '#f2f2f2', 
				'z-index' : '50',
			});
			$('.blockPage').css({
				'position' : 'absolute',
				'top' : '0',
				'left' : '0',
				'background' : 'rgb(0,0,0,0.6)',
				'height' : pageHeight,
				'width' : pageWidth,
				'z-index' : '10'
			});
			$('.dialogClose').css({
				'top' : '-25px',
				'left' : '20px',
				'float' : 'right',
				'display' : 'block',
				'height' : '50px',
				'width' : '50px',
				'background' : 'url(images/cancel.png) no-repeat',
				'position' : 'absolute'
			});
			$('.blockPage').css({
				'position' : 'absolute',
				'top' : '0',
				'left' : '0',
				'background-color' : 'rgba(0,0,0,0.6)',
				'height' : pageHeight,
				'width' : pageWidth,
				'z-index': '10'
			});
			$('.dialogInnerModal').css({
				'background-color' : '#fff',
				'height' : (options.height - 50) + 'px',
				'width' : (options.width - 50) + 'px',
				'padding' : '10px',
				'margin' : '15px',
				'border-radius' : '10px',
			});
		}
		function add_popup_box(){
			var pop_up = $('<div class="dialog"><a href="#" class="dialogClose"></a><div class="dialogInnerModal"><h2>'+options.title+'</h2><p>'+options.description+'</p></div></div>');
			$(pop_up).appendTo('.blockPage');
			$('.dialogClose').click(function(){
				$('.blockPage').fadeOut().remove();
				$(this).parent().fadeOut().remove();
			});
		}
		function add_block_page(){
			var blockPage = $('<div class="blockPage"></div>');
			$(blockPage).appendTo('body');
		}
		return this;
	};
})(jQuery);