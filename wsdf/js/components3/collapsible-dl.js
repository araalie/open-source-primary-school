/**
 * @author albert
 */

/* Collapsible Definition List usando jQuery */
(function( $ ){
$.fn.collapsible_dl = function() {
   return this.each(function(){  
		/* ocultamos todos los <dd>, agregamos la clase 'more' a todos los <dt> y añadimos el evento 'click' a todos los <dt> */
		$(this).find('dd').hide().end().find('dt').addClass("more").bind({
			click: function(){
				/* si algun <dt> tiene la clase 'more', la eliminamos y agreamos la clase 'less' */
				$(this).toggleClass("more less");
				/* mostrando todos los <dd> hasta topar con algun <dt>*/
				$(this).nextAll().each(function(){
					if($(this).is('dt')){
						return false;
					}
					$(this).slideToggle();
				});			
			}
		}); 
   });  
};
})( jQuery );
