/*
 *  Here is to set up some global variables
 */
var g_selected_id = '';
var g_menu_id = '';
var g_menu_type = ''; 


function loadMenuTree () {
	$.ajax ( {
		type: "GET",
        url: "admins/listMenus/?unique="+(new Date()).getTime(),
        beforeSend: function() { $("#main_left").html("<img src='img/icon_load.gif'>"); },
        success: function ( msg ) { 
        	display ( msg, "#main_left" );
        	loadMenuActions (); 
        }
	});
}

function loadMenuActions () {
	$("#menu_tree li a").click ( function () {
		var ajaxControl = $(this).attr ( 'ctrl' );
		var ajaxTreeUrl = ajaxControl.toLowerCase + "/tree" + ajaxControl;
		var ajaxListUrl = ajaxControl.toLowerCase + "/list" + ajaxControl;
		ajaxTreeUrl += "/?unique=" + (new Date()).getTime();
		ajaxListUrl += "/?unique=" + (new Date()).getTime();
		
		var parent_menu_id = $(this).parent().parent().prev().attr('id');
		var prev_menu_id = g_menu_id;
		g_menu_id = $(this).attr ( 'id' );

		if ( g_menu_id != prev_menu_id ) {
			if ( prev_menu_id != '' && parent_menu_id != prev_menu_id ) {
				$("#menu_tree li[@id='"+prev_menu_id+"'] ul").slideUp ('normal');
			}
			$.ajax ( {
				type: "GET",
				url: ajaxTreeUrl,
				success: function ( msg ) {
					$("#menu_tree li[@id='"+g_menu_id+"'] ul").empty().remove();
            		$("#menu_tree li[@id='"+g_menu_id+"']").append ( msg );
            		$("#menu_tree li[@id='"+g_menu_id+"'] ul").slideDown('normal'); 
            		loadMenuActions ();
				}
			});
		}
		$.ajax ( {
			type: "GET",
            url: ajaxListUrl,
            beforeSend: function() { $("#main_right_content").html("<img src='img/icon_load.gif'>"); },
            success: function ( msg ) { display ( msg, "#main_right_content" ); }
		});
	});
}

function display ( content, strId ) {
	$(strId).html ( content );
	loadEffects ();
}

/*
 *  The following code is to initialize page
 */

$(document).ready ( function() {
	loadSplitter ();
	loadMenuTree ();
	loadActions ();
});