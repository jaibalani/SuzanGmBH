            
            $(document).ready(function(){
            
                    // ADD SLIDEDOWN ANIMATION TO DROPDOWN //
                   $('.dropdown').on('show.bs.dropdown', function(e){
                     $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
                   });

                   // ADD SLIDEUP ANIMATION TO DROPDOWN //
                   $('.dropdown').on('hide.bs.dropdown', function(e){
                     $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
                   });
		
		/*
		//Left Menu Vertical Accordian

		$("#accordian h3").click(function(){
            
		//slide up all the link lists
		    $("#accordian ul ul").slideUp();
		    
		    //slide down the link list below the h3 clicked - only if its closed
		    if(!$(this).next().is(":visible"))
		    {  
		        $(this).next().slideDown();
		    }
		    
		    //Check submenu Exist or not
		    if ($(this).siblings().size() > 0){
		       
		        
		        if(!$(this).parent().hasClass("active_opt")){
		        
		            $('#accordian ul li').removeClass('active_opt');
		            $(this).parent().addClass('active_opt');
		        
		        }else{
		            
		            $(this).parent().removeClass('active_opt');
		            
		        }   
		        
		       
		        
		    }
		    
		    
		});	

		*/
		
           
            });
