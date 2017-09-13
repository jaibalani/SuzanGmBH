$(document).ready(function(){
    
    $('.sb-has-submenu h3').click(function(){
          
          $(this).next().toggle('sliderup'); 
          if(!$(this).hasClass('opt-selected')){
              $(this).addClass('opt-selected');
          }else{
              $(this).removeClass('opt-selected');
          }
    });
    
    /*filter js*/
    $('.main-filter-opt').hover(function(){
            
           if(!$(this).find('.filter-title').hasClass('selected-main-filter')){ 
                $('.filter-option').hide();
                $('.filter-title').removeClass('selected-main-filter');
                $(this).find('.filter-option').toggle();
                $(this).find('.filter-title').addClass('selected-main-filter');
            }else{
                $(this).find('.filter-option').toggle();
                $(this).find('.filter-title').removeClass('selected-main-filter');
            }
    });
    
    //  /* online calling card*/
           $('#sb_card_names ul li').mouseenter(function(){
              
                cardDiv_position = parseInt($('#sb_card_names').offset().left);
                arrow_position = parseInt($('#sb_scroll_btn').offset().left);
                scroll_window = arrow_position - cardDiv_position;
               
                card_position = parseInt($(this).offset().left);
                card_opt_width = parseInt($(this).width());

                right_hidden = card_position - cardDiv_position + card_opt_width;
                left_hidden = card_position - cardDiv_position;

                if(right_hidden > 610){
                    $(this).find('.sub-card-menu').css('margin-left', '-170px');
                    $(this).find('.sub-card-menu').css('display','');
                }else{
                  $(this).find('.sub-card-menu').css('margin-left', '');
                  $(this).find('.sub-card-menu').css('margin-left', '-80px');
                }

                if(left_hidden > -14 && left_hidden < 65){
                    $(this).find('.sub-card-menu').css('margin-left', '-12px');
                }

                if(left_hidden < -12 || right_hidden > 720){
                    scroll_area = right_hidden - 720 + 20;
                    if(left_hidden >= 0){
                        $('#sb_card_names').animate( { scrollLeft: '+='+scroll_area }, 1000);
                    }else{
                        $('#sb_card_names').animate( { scrollLeft: '+='+left_hidden }, 1000);
                        $(this).find('.sub-card-menu').css('margin-left', '-12px');
                    }
                }
            }); 

           $('#sb_card_names ul li').mouseleave(function(){

           });
    // /* online calling card*/

    /*Faqs js for accordin*/
    
    $('.faq-items').click(function(){
        
        if(!$(this).find('.faqs-icon').hasClass('faqs-icon-active')){
            $('.faq-details').slideUp();
            $('.faqs-icon').html('+').removeClass('faqs-icon-active');
            $(this).find('.faq-details').toggle('sliderup');
            $(this).find('.faqs-icon').html('&ndash;').addClass('faqs-icon-active');
        }
        
    });
    
});

$(window).load(function() {
         
         /* Online_card page card name horizontal scroll js start   */
        
        var ul_width = 0;
        $('#sb_card_names ul li').each(function() {
            //ul_width += $(this).outerWidth(false);
            ul_width += $(this).innerWidth();
        });
        
        total_width = parseInt(ul_width)+20;
        
       if(total_width<760){
          total_width=710;
          $('#sb_scroll_btn').html('');
          $('#sb_card_names').css('overflow','inherit');
       }else{
    	   $('#sb_card_names').css('overflow','hidden');
    	   total_width = total_width + 100;
       }
       $('#sb_card_names ul').width(total_width);
       
       if(total_width > 760){
    	   	$('#sb_scroll_btn').html(' <a id="prev_btn" class="buttons prev lanslideleftarrow" href="#">&lt;</a> <a  id="next_btn" class="buttons next lansliderightarrow" href="#">&gt;</a>');
	         $('#prev_btn').on('click',function(){
	             
	             
	           $('#sb_card_names').stop();
	            
	            $('#sb_card_names').animate({
	               
	                scrollLeft: '+=200'
	            }, 1000);
	          event.preventDefault();  
	            
	        });
	        
	        $('#next_btn').on('click', function(){
	          
	           $('#sb_card_names').stop();
	        
	            $('#sb_card_names').animate({
	               
	                scrollLeft: '-=200'
	            }, 1000);
	          event.preventDefault();  
	            
	        });
		}
     
        
        /* Online_card page card name horizontal scroll js end   */
         
    });
    