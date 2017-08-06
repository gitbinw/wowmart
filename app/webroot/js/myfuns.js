/****Global variables setting****/
var g_tree_id = 'menu_tree';
var g_tree_body = 'main_left_content';
var g_body_id = 'main_right_content';
var g_list_id = 'table_list';
var g_form_id = 'form_detail';
var g_nochild_class = 'no_node_child';
var g_button_stats = 'action_button_status';
var g_list_ctrl = '';
var g_menu_id = '';
var g_menu_params = '';
var g_menu_ctrl = null;
var g_menu_data = null;
var g_selt_items = new Array();
var g_category_index = 0;
var g_site_root = '';

var g_selt_bg = 'g_selt_bg';
var g_hove_bg = 'g_hove_bg';
var g_init_bg = 'g_init_bg';

var g_tree_menus = ['groups', 'categories', 'pages'];

/****General helps functions****/
function in_array(arr, val) {
	for(var i=0; i<arr.length; i++) {
		if (val == arr[i]) return true;
	}
	return false;
}
function loading () {
  $("#"+g_body_id).html("<div id='processing_icon'></div>");
}
function loading_tree(){
  $("#"+g_tree_body).html("<div id='processing_icon'></div>");
}
function category ( index, msg ) {
	if ( index == null ) index = 0;
	else index++;
	if ( $("#category_"+index).html () != null ) {
		if ( msg != null ) {
			if ( msg == 0 ) {
				$("#category_"+index).empty().remove();
			} else {
				$("#category_"+index).html ( msg );
			}
		}
	} else {
		if ( msg == 0 ) return false;
		var newTr = "<tr id='category_"+index+"' name='sub_cat'>"+msg+"</tr>";
		$("#category_"+(index-1)).after(newTr);
	}
	$("#category_"+index+" select").change(loadCategory);
}
function loadCategory () {
	var pid = $(this).val();
	var objTr = $(this).parent().parent();
	var strId = objTr.attr('id');
	var arrId = strId.split('_');
	var intId = arrId[1];

	if ( pid == 0 ) {
		objTr.nextAll("tr[name|='sub_cat']").empty().remove();
		if ( intId > 1 ) g_category_index = intId - 1;
		else g_category_index = 0;
		return false;
	}

	if (g_menu_ctrl == 'products') {
		thisUrl = g_site_root + "/admin/categories/get/"+pid;
	} else if (g_menu_ctrl == 'users') {
		thisUrl = g_site_root + "/admin/groups/get/"+pid;
	}
	$.ajax ( {
		type:'GET',
        url: thisUrl,
        success: function ( msg ) {
        	g_category_index = intId;
        	objTr.next().nextAll("tr[name|='sub_cat']").empty().remove();
            category ( intId, msg );
        }
	} );
}
function handleFeature (obj) {
	var objTb = document.getElementById('table_feature');
	if (obj.className == 'add_feature') {
		var tr = "<tr><td class='form_label'>Feature:</td>"+
			 	 "<td><input type='text' name='data[Feature][][feature]'></td>"+
			 	 "<td class='del_feature' onclick='handleFeature(this);'></td></tr>";
		$('#table_feature').append(tr);
		//obj.className = 'del_feature';
		afterPageLoaded();
	} else if (obj.className == 'del_feature') {
		if (confirm('Are you sure to remove this feature?')) {
			objTb.childNodes[0].removeChild(obj.parentNode);
			afterPageLoaded();
		}
	}
	$("#table_feature td[class|='add_feature']").parent().find('input').focus();
}
function handleFreight (obj) {
	var objTb = document.getElementById('table_freight');
	if (obj.className == 'add_freight') {
		var num = $("#table_freight tr.tr_freight").length;
		var tr = "<tr class='tr_freight'><td><b>Qty Range:</b><input type='text' " +
					   " name='data[Freight][" + num + "][minQty]' value='' />--<input type='text' " + 
					   " name='data[Freight][" + num + "][maxQty]' value='' />" + 
					   "&nbsp;&nbsp;<b>Freight:</b>&nbsp;$<input type='text' " +
					   " name='data[Freight][" + num + "][freight]' value='' />" + 
					   "</td><td class='del_freight' onclick='handleFreight(this);'></td></tr>";
		$('#table_freight').append(tr);
		//obj.className = 'del_freight';
		afterPageLoaded();
	} else if (obj.className == 'del_freight') {
		if (confirm('Are you sure to remove this freight?')) {
			objTb.childNodes[0].removeChild(obj.parentNode);
			afterPageLoaded();
		}
	}
	$("#table_freight td[class|='add_freight']").parent().find('input').focus();
}
function handleSubproduct (obj) {
	var objTb = document.getElementById('table_subproduct');
	if (obj.className == 'add_subproduct') {
		var num = $("#table_subproduct tr.tr_subproduct").length;
		var tr = "<tr class='tr_subproduct'><td><b>Name:</b><input type='text' " +
					   " class='long' name='data[Subproduct][" + num + "][name]' value='' />" + 
					   "&nbsp;<b>Price:</b><input type='text' " + 
					   " name='data[Subproduct][" + num + "][price]' value='' class='short_field' />" + 
					   "&nbsp;<select name='data[Subproduct][" + num + "][prod_type]' class='medium_field'>" +
					   "<option value=''>Select Type</option>" + 
					   "<option value='sidedish'>Side Dish</option>" + 
					   "<option value='drink'>Drink</option>" +
					   "</select>" + 
					   "&nbsp;<b>Factor:</b><input type='text' " +
					   " name='data[Subproduct][" + num + "][factor]' value='' class='short_field' />" + 
					   "&nbsp;&nbsp;<input type='radio' class='radio' name='rad_dummy' /><b>Default</b>" +
					   "<input type='hidden' class='rad_subprod' " + 
					   " name='data[Subproduct][" + num + "][is_default]' value=0 />" + 
					   "</td><td class='del_subproduct' onclick='handleSubproduct(this);'></td></tr>";
		$('#table_subproduct').append(tr);
		//obj.className = 'del_subproduct';
		afterPageLoaded();
	} else if (obj.className == 'del_subproduct') {
		if (confirm('Are you sure to remove this sub-product?')) {
			objTb.childNodes[0].removeChild(obj.parentNode);
			afterPageLoaded();
		}
	}
	$("#table_subproduct td[class|='add_subproduct']").parent().find('input').focus();
}
function handleMedia (obj, mod) {
	var modelVal = "";
	if (mod != null) modelVal = mod;
	var objTb = document.getElementById('table_media');
	if (obj.className == 'add_media') {
		var num = $("textarea.media_textarea").length; 
		var tr = "<tr><td class='form_label'>Youtube:</td>"+
			 	 "<td><textarea name='data[Media][" + num + "][scripts]' class='media_textarea'></textarea>" + 
			 	 "<input type='hidden' name='data[Media][" + num + "][model]' value='" + modelVal + "' /></td>"+
			 	 "<td class='del_media' onclick='handleMedia(this);'></td></tr>";
		$('#table_media').append(tr);
		//obj.className = 'del_media';
		afterPageLoaded();
	} else if (obj.className == 'del_media') {
		if (confirm('Are you sure to remove this media?')) {
			objTb.childNodes[0].removeChild(obj.parentNode);
			afterPageLoaded();
		}
	}
	$("#table_media td[class|='add_media']").parent().find('textarea').focus();
}

/****Splitter panel function****/
function splitPanel () {
  $("#main_body").splitpanel({
		leftPanelID  : 'main_left',
		rightPanelID : 'main_right',
		fullScreenID : ['container'],
		slidableBar  : true
	});
}

/****Menu tree functions****/
function menuUrl (action,params) {
  if (g_menu_ctrl==null)g_menu_ctrl='console';
  if (params==null || params=='undefined')params='';
  else params = '/'+params;
  return g_site_root + '/admin/' + g_menu_ctrl+'/'+action+'/'+g_menu_id+params;
}
function menuClick (id) {
	var $id = $(id);
  $id.each(function(i, val) {
	  var $val = $(val),
	  	$parent = $val.parent();
	
	 // if($parent.parent().parent().attr('id') != 'main_left_content') {
		  if ($val.next('ul').length > 0) $parent.addClass('folder-end');
  	  	  else $parent.addClass('node-end');
	//  } else {
	 // 	  $parent.addClass('folder-end');
	//  }
  });
  
  $id.unbind().click(function(){
    var parent_menu_id = $(this).parent().parent().prev().attr('id');
    var parent_menu_ctrl = $(this).parent().parent().prev().attr('ctrl');
    var prev_menu_id = g_menu_id;
    var prev_menu_ctrl = g_menu_ctrl;
    g_menu_id = $(this).attr('id'); 
    g_menu_ctrl = $(this).attr('ctrl');
	g_menu_data = $(this).attr('data-menu');
    var params = $(this).attr('args');
	g_menu_params = params;
	
	var $key = $("#keywords");
	var strData = "";
	if ($key.length > 0 && $.trim($key.val()) != '') strData='keywords=' + $key.val();
    $.ajax ({
   		type: "POST",
   		url: menuUrl('view',params),
		data: strData,
   		beforeSend: loading,
      success: function(msg) {
      	display(msg,g_body_id);
      }
    });
    
    /*------------Menu effects----------------*/
    $("#"+g_tree_id+" li a[id|='"+prev_menu_id+"'][ctrl|='"+prev_menu_ctrl+"']")
    .css ({backgroundColor:'transparent',color:'#000000'});
    $(this).parent().siblings().find('ul:visible').hide();                          //slideUp('fast');
    $(this).parent().siblings(':not(.node-end)').removeClass('folder-open')
    	.find('ul li:not(.node-end)').removeClass('folder-open');
    $(this).css ({backgroundColor:'#316AC5',color:'#FFFFFF'});
    $(this).parent(':not(.node-end)').addClass('folder-open');
    if ($(this).next('ul').css('display')=='none') {
        $(this).next('ul').show();                                              //slideDown('normal');
    } else {
        $(this).next().children().find('ul:visible').hide();                    //slideUp('fast');
        $(this).next().children().find('ul li:not(.node-end)').removeClass('folder-open');
    }

  });
}
function menuAnchor (id) {
    var parent_menu_id = $(id).parent().parent().prev().attr('id');
    var parent_menu_ctrl = $(id).parent().parent().prev().attr('ctrl');
    var prev_menu_id = g_menu_id;
    var prev_menu_ctrl = g_menu_ctrl;
    g_menu_id = $(id).attr('id');
    g_menu_ctrl = $(id).attr('ctrl');
    /*------------Menu effects----------------*/
    $("#"+g_tree_id+" li a[id|='"+prev_menu_id+"'][ctrl|='"+prev_menu_ctrl+"']")
    .css ({backgroundColor:'transparent',color:'#000000'});
    $(id).parent().siblings().find('ul:visible').hide();                          //slideUp('fast');
    $(id).parent().siblings(':not(.node-end)').removeClass('folder-open')
    .find('ul li:not(.node-end)').removeClass('folder-open');
    $(id).css ({backgroundColor:'#316AC5',color:'#FFFFFF'});
    $(id).parent(':not(.node-end)').addClass('folder-open');
    if ($(id).next('ul').css('display')=='none') {
        $(id).next('ul').show();                                              //slideDown('normal');
    } else {
        $(id).next().children().find('ul:visible').hide();                    //slideUp('fast');
        $(id).next().children().find('ul li:not(.node-end)').removeClass('folder-open');
    }	
}
function menuUpdate () {
    var strNew = $("#main_right_content input[id|='new_node']").attr('value');
    if (strNew == null || strNew == "" ) return false;
    
	var arrRes = unserialize ( strNew );
    var newMenu = "<li><a id='"+arrRes['id']+"' ctrl='"+arrRes['ctrl']+"'>"+arrRes['name']+"</a></li>";
	var mainMenu = $("#menu_tree li a[id|='"+g_menu_id+"'][ctrl|='"+g_menu_ctrl+"']").next('ul');
	var subMenu = $("#menu_tree li a[id|='"+arrRes['id']+"'][ctrl|='"+arrRes['ctrl']+"']");
	if ( subMenu.html() == null || subMenu.html() == 'undefined' ) {
		if ( mainMenu.html() == null ) {
			$("#menu_tree li a[id|='"+g_menu_id+"'][ctrl|='"+g_menu_ctrl+"']")
				.after("<ul>"+newMenu+"</ul>")
				.parent().removeClass('node-end').addClass('folder-end folder-open');
		} else {
			mainMenu.append(newMenu);
		}
        menuClick("#menu_tree li a[id|='"+arrRes['id']+"'][ctrl|='"+arrRes['ctrl']+"']");
	} else {
		subMenu.text ( arrRes['name'] );
	}
}
function menuRemove () {
        var arrMenu = g_selt_items;
    for ( var i in arrMenu ) {
		var $p = $("#menu_tree li a[id|='"+arrMenu[i]['id']+"'][ctrl|='"+g_menu_ctrl+"']").parent(),
			$pp = $p.parent();
        
		$p.empty().remove();
		if ($pp.is(':empty')) {
			$pp.parent().removeClass('folder-end, folder-open').addClass('node-end');
			$pp.remove();
		}
    }
}
function menuLoad(){
  g_menu_ctrl=null;
  $.ajax ({
  	type: "POST",
  	url:menuUrl ('menu'),
  	beforeSend:loading_tree,
    success:function(msg) {
    	display (msg,g_tree_body);
      	$("#"+g_tree_id+" li ul").css('display','none');
	  
	  	var $menuList = $('#menu_allow_child');
		if ($menuList.length) {
	 		var strList = $.trim( $menuList.val() ),
				arrList = strList.split(',');

			g_tree_menus = arrList;
		}
		
		menuClick("#"+g_tree_id+" li a");
    }
  });
  
  viewCart();
}

/****Action handling functions****/
function searchKeyword() {
	var params = $("#menu_tree li a[id|="+g_menu_id+"][ctrl|="+g_menu_ctrl+"]").attr('args');
	var $key = $("#keywords");
	var strData = "";
	if ($key.length > 0 && $.trim($key.val()) != '' && g_menu_id != '') {
		strData='keywords=' + $key.val();
		$.ajax ({
			type: "POST",
			url: menuUrl('view',params),
			data: strData,
			beforeSend: loading,
			success: function(msg) {
				display(msg,g_body_id);
			}
		});
	}
}
function handleSearch() {
	$("#keywords").keydown(function(ev) {
		if (ev.keyCode == 13) {
			searchKeyword();
		}
	});
	$("#btn_search").click(function(e) {
    	searchKeyword();
	});
}
function getParams() {
	var $key = $("#keywords");
	var strData = "";
	if ($key.length > 0 && $.trim($key.val()) != '') strData='keywords=' + $key.val() + '&';
	
	var params = strData + 'selItems=' + serialize(g_selt_items);
	return params;
}
function actUrl (action) {
	var txtCtrl = $('#' + g_list_id).length ? $.trim( $('#' + g_list_id).attr('ctrl') ) : '',
		ctrl = txtCtrl ? txtCtrl : g_menu_ctrl;
	//var args = $('#'+g_menu_id).attr('args');
	//if (args == null || args == 'undefined' || args == '') args = '';
	//else args = '/'+args;
	var args = '';
	if (g_menu_params != null && g_menu_params != '' && g_menu_params != 'undefined' ) {
		args = '/' + g_menu_params;
	}
	
	return g_site_root + '/admin/' + ctrl+'/'+action+'/'+g_menu_id + args;
}
function addNewItem ( objTr ) {
        var arrTmp = new Array ();
        arrTmp [ 'id' ] = objTr.attr ( 'id' );
       // arrTmp [ 'ctrl' ] = objTr.attr ( 'ctrl' );
        g_selt_items.push ( arrTmp );
        switchMenu ();
}
function removeItem ( objTr ) {
        var id = objTr.attr ( 'id' );
        for ( var i in g_selt_items ) {
                if ( g_selt_items [ i ][ 'id' ] == id ) {
                        g_selt_items.splice ( i, 1 );
                }
        }
        switchMenu ();
}
function isSelected ( id ) {
        for ( var i in g_selt_items ) {
                if ( id == g_selt_items [i]['id'] ) {
                        return true;
                }
        }
        return false;
}
function loadEffects () {
        chk_id = '#'+g_list_id+' tr[id] input,#'+g_list_id+' tr[id] input';
        tr_id = '#'+g_list_id+' tr[id],#'+g_list_id+' tr[id]';

        $(chk_id).unbind().click ( function (e) {
				e.stopPropagation();
                var objChk = $(this),
					$tr = objChk.parent().parent();

                if ( objChk.is(':checked') ) {
						addNewItem ( $tr );
                      //  objChk.attr ( 'checked', false );
						$tr.css ( {backgroundColor:'#FFB7B7',color:'#FFFFFF'} );
                } else {
                        removeItem ( $tr );
                       // objChk.attr ( 'checked', true );
                        $tr.css ( {backgroundColor:'#FFFFFF',color:'#000000'} );
                }
        } );
        $(tr_id).unbind().click ( function (e) {
                var $this = $(this),
					objChk = $this.find ("input[type=checkbox]:first");
                if ( objChk.is(':checked') ) {
                        removeItem ( $this );
                        objChk.get(0).checked = false;
                        $this.css ( {backgroundColor:'#FFFFFF',color:'#000000'} );
                } else {
                        addNewItem ( $this );
                        objChk.get(0).checked = true;
                        $this.css ( {backgroundColor:'#FFB7B7',color:'#FFFFFF'} );
                }
        } ).mouseover ( function () {
                if ( !isSelected ( $(this).attr ( 'id' ) ) ) {
                        $(this).css ( {backgroundColor:'#FF9933',color:'#FFFFFF'} );
                }
        } ).mouseout ( function () {
                if ( !isSelected ( $(this).attr ( 'id' ) ) ) {
                        $(this).css ( {backgroundColor:'#FFFFFF',color:'#000000'} );
	 }
        } );
}

/****Action enable/disable functions****/
function disableButtons() {
	var $rightBody = $('#' + g_body_id),
		strStatus = $('#' + g_button_stats, $rightBody).val();
	if (strStatus && strStatus.indexOf('disable_new') !== -1) {	
		disableNew();
	}
}
function switchMenu () {
  	if ( g_menu_ctrl != null && g_menu_ctrl != '' 
			&& (g_menu_ctrl != 'console' || g_menu_data == 'admin')
  			&& g_menu_ctrl != 'orders' && g_menu_ctrl != 'invoices') {
		enableNew();
        } else {
                disableNew();
        }
        if ( document.getElementById(g_form_id) != null ) {
		enableSave();
	} else {
		disableSave();
	}
	if (document.getElementById('email_content') != null) {
		enablePrint();
		//enableWord();
		enablePdf();
	} else {
		disablePrint();
		//disableWord();
		disablePdf();
	}
    if ( g_selt_items.length > 1 ) {
        if (g_menu_ctrl == 'products') {
        	enableBuy();
        }
        if (g_menu_ctrl != 'orders') {
        	enableDelete();
        }
        disableEdit();
    } else if ( g_selt_items.length == 1 ) {
       	if (g_menu_ctrl == 'products') {
        	enableBuy();
        }
        enableEdit();
        if (g_menu_ctrl != 'orders') {
        	enableDelete();
        }
    } else {
       	disableBuy();
        disableEdit();
        disableDelete();
    }
	
	disableButtons();
}
function enableNew () {
        $("#menu_right_new").unbind().click ( function(){
			$.ajax ( { type: "GET",url: menuUrl ( 'new' ),beforeSend:loading,success:function(msg) {
				display (msg,g_body_id);
				if ( g_menu_ctrl == 'products' || g_menu_ctrl == 'users' ) {
            		category ();
            	}
			}});
        }).css({color:'black',cursor:'pointer',backgroundImage:'url(img/icons/new_active.gif)'});

}
function disableNew () {
        $("#menu_right_new").unbind().css({color:'#CCCCCC',cursor:'default',backgroundImage:'url(img/icons/new_inactive.gif)'});
}
function enableEdit () {
	$("#menu_right_edit").unbind().click ( function(){
		$.ajax ( { 
			type: "POST",
			url: actUrl ('edit'),
			data: getParams(),
			beforeSend:loading,
			success: function ( msg ) {
				display ( msg, g_body_id );
				var obj = $("tr[name|='cat'] select,tr[name|='sub_cat'] select");
				var i = 0;
				$.each(obj,function() {
					if ( $.trim( $(this).val() ) ) {
						i ++;
					}
				});
				//g_category_index = i>1?i-1:0;
				g_category_index = i - 1;

				$("tr[name|='cat'] select,tr[name|='sub_cat'] select").change(loadCategory);                                
			}
		});
	})
	.css({color:'black',cursor:'pointer',backgroundImage:'url(img/icons/edit_active.png)'});
}
function disableEdit () {
        $("#menu_right_edit").unbind().css({color:'#CCCCCC',cursor:'default',backgroundImage:'url(img/icons/edit_inactive.gif)'});
}
function enableDelete () {
	$("#menu_right_delete").unbind().click ( function () {
		if ( confirm('Are your sure to delete the selected items?') ) {
			$.ajax ( { 
				type: "POST",
				url: actUrl ( 'delete' ),
				data: getParams(),
				beforeSend:loading, 
				success: function ( msg ) {
					if ( in_array(g_tree_menus, g_menu_ctrl) ) { 
						menuRemove(); 
					}
          display ( msg, g_body_id );
        }
      });
    }
  }).css({color:'black',cursor:'pointer',backgroundImage:'url(img/icons/del_active.gif)'});
}
function disableDelete () {
        $("#menu_right_delete").unbind().css({color:'#CCCCCC',cursor:'default',backgroundImage:'url(img/icons/del_inactive.gif)'});
}
function enableBuy () {
        $("#menu_right_buy").unbind().click ( function () {
                $.ajax ( { type: "GET",url: actUrl ( 'buyIt' ),beforeSend:loading, success: function ( msg ) {
                	menuAnchor("#menu_tree li a[id|='0'][ctrl|='orders'][args|='']");
                	display ( msg, g_body_id );
                }
                });
        }).css({color:'black',cursor:'pointer',backgroundImage:'url(img/icons/buy_active.gif)'});
}
function disableBuy () {
        $("#menu_right_buy").unbind().css({color:'#CCCCCC',cursor:'default',backgroundImage:'url(img/icons/buy_inactive.gif)'});
}
function enablePrint () {
        $("#menu_right_print").unbind().click ( function () {
                docPrint('email_content');
        }).css({display:'block'});
}
function disablePrint () {
        $("#menu_right_print").unbind().css({display:'none'});
}
function enableWord () {
        $("#menu_right_word").unbind().click ( function () {
        		var id = $('#email_content').attr('pid');
                window.open(actUrl('word')+'/'+id);
        }).css({display:'block'});
}
function disableWord () {
        $("#menu_right_word").unbind().css({display:'none'});
}
function enablePdf () {
        $("#menu_right_pdf").unbind().click ( function () {
        		var id = $('#order_id').val();
            window.open(actUrl('output')+'/type:pdf/pid:'+id);
        }).css({display:'block'});
}
function disablePdf () {
        $("#menu_right_pdf").unbind().css({display:'none'});
}
function enableSave () {
        $("#menu_right_save").unbind().click ( function () {
        		if ( g_menu_ctrl == 'products' ) {
            		$("#category_"+g_category_index+" select").attr('name','data[Category][Category][]');
        		} else if ( g_menu_ctrl == 'users' ) {
        			$("#category_"+g_category_index+" select").attr('name','data[Group][Group][]');
        		}
			
			var $key = $("#keywords");
	var strData = "",
		$form = $("#"+g_form_id);
	if ($key.length > 0 && $.trim($key.val()) != '') strData='&keywords=' + $key.val();
	
            $.ajax ( { 
              type: "POST",
              url: actUrl ( 'save' ),
              data: $form.serialize() + strData,
              beforeSend:loading,
              success:function(msg){
								display (msg, g_body_id);
								$("tr[name|='cat'] select,tr[name|='sub_cat'] select").change(loadCategory);
                 	if (  in_array(g_tree_menus, g_menu_ctrl) && !$form.hasClass(g_nochild_class)) { 
                  	menuUpdate (); 
                 	}
             	}
             });
        }).css({color:'black',cursor:'pointer',backgroundImage:'url(img/icons/save_active.png)'});
}
function disableSave () {
        $("#menu_right_save").unbind().css({color:'#CCCCCC',cursor:'default',backgroundImage:'url(img/icons/save_inactive.gif)'});
}

/****Content display functions****/
function uploadImage () {
	var objForm = document.form_file;
	var objOld = document.getElementById('main_image');
	if ( objForm != null ) {
		if ( objOld != null && objOld.value != '' ) {
			$("#form_file").append("<input type='hidden' name='data[old]' value='"+objOld.value+"'");
		}
		objForm.submit();
		objForm.reset();
	}
}
function display ( content, strId ) {
  g_selt_items = new Array ();
  $('#'+strId).html ( content );
  afterPageLoaded();
  if (document.getElementById(g_list_id) != null) {
	  loadEffects ();
      handleSortColumn();
  }
  selOrderClient();
  
  switchMenu();
 // paginate();
}

function call(thisUrl,flg) {
	if (flg != null && flg == 1) {
		var obj=document.getElementById('order_sel_client');
		if ( obj != null && obj.value != 0) {
			thisUrl += '/'+obj.value;
		} else {
			alert ('Please select one client before you check out!');
			return false;
		}
	}
	if (flg != null && flg == 2) {
		$.ajax ( { type: "POST",url: thisUrl,beforeSend:loading,success:function(msg){
			menuAnchor("#menu_tree li a[id|='0'][ctrl|='invoices'][args|='']");
			display (msg, g_body_id);
    	}});
	} else {
		$.ajax ( { type: "POST",url: thisUrl,data: $("#form_cart").formSerialize(),beforeSend:loading,success:function(msg){
			display (msg, g_body_id);
    	}});
	}
}

function sure(flg,str) {
	if (flg == 1) {
		msg = 'Are you sure to clear the whole shopping cart?';
	} else if (flg == 2) {
		msg = 'Are you sure to delete the item \"'+str+'\" \nfrom your shopping cart?';
	} else if (flg == 3) {
		msg = 'Are you sure to cancel this order \"'+str+'\" ?';
	} else if (flg == 4) {
		msg = 'Are you sure to set this order status to \"'+str+'\" ?';
	} else if (flg == 5) {
		msg = 'Are you sure to remove the status \"'+str+'\" from this order ?';
	}

	return confirm(msg);
}

function setStatus(thisUrl) {
	$.ajax ( { type: "GET",url: thisUrl,success:function(msg){
			$('#order_status_list').html(msg);
    }});
}

function viewCart() {
	$("#menu_right_cart").unbind().click ( function () {
		$.ajax ( { type: "GET",url: 'shops/view',beforeSend:loading, success: function ( msg ) {
				menuAnchor("#menu_tree li a[id|='0'][ctrl|='orders'][args|='']");
				display ( msg, g_body_id );
			}
    	});
	});
}
function numberFormat(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num)) num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
		num = num.substring(0,num.length-(4*i+3))+','+
		num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num + '.' + cents);
}
function calCart(obj,amount,pgst) {
	var freight = parseFloat(obj.value);
//	if (isNaN(freight)) {
//		freight = 0;
//		obj.value = 0;
//	}
	if (obj.value == '') freight = 0;
	if (obj.value!='' && !obj.value.match(/[0-9.]+$/g)) {
		obj.value = 0;
		freight = 0;
	}  
	var subtotal = parseFloat(amount) + freight;
	var gst = parseFloat(pgst) * subtotal;
	var total = gst + subtotal;
	$('#cart_gst').html(numberFormat(gst));
	$('#cart_sub').html(numberFormat(subtotal));
	$('#cart_total').html(numberFormat(total));
}

function selOrderClient() {
	if (document.getElementById('order_sel_client') != null) {
		$('#order_sel_client').unbind().change( function() {
			var pid = $(this).attr('value');
			$.ajax ( { async:true,type: "GET",url:'users/getClient/'+pid,success: function ( msg ) {
					if (msg == 0) {
						$("#form_cart input[id|='txt_b_company']").attr('value','');
						$("#form_cart input[id|='txt_b_fname']").attr('value','');
						$("#form_cart input[id|='txt_b_lname']").attr('value','');
						$("#form_cart input[id|='txt_b_email']").attr('value','');
						$("#form_cart input[id|='txt_b_phone']").attr('value','');
						$("#form_cart input[id|='txt_b_fax']").attr('value','');
						$("#form_cart input[id|='txt_b_mobile']").attr('value','');
						$("#form_cart input[id|='txt_b_address']").attr('value','');
						$("#form_cart input[id|='txt_b_suburb']").attr('value','');
						$("#form_cart input[id|='txt_b_state']").attr('value','');
						$("#form_cart input[id|='txt_b_postcode']").attr('value','');	
					} else {
						var arrC=unserialize(msg);
						$("#form_cart input[id|='txt_b_company']").attr('value',arrC['ClientProfile']['company']);
						$("#form_cart input[id|='txt_b_fname']").attr('value',arrC['ClientProfile']['firstname']);
						$("#form_cart input[id|='txt_b_lname']").attr('value',arrC['ClientProfile']['lastname']);
						$("#form_cart input[id|='txt_b_email']").attr('value',arrC['ClientProfile']['email']);
						$("#form_cart input[id|='txt_b_phone']").attr('value',arrC['ClientProfile']['phone']);
						$("#form_cart input[id|='txt_b_fax']").attr('value',arrC['ClientProfile']['fax']);
						$("#form_cart input[id|='txt_b_mobile']").attr('value',arrC['ClientProfile']['mobile']);
						$("#form_cart input[id|='txt_b_address']").attr('value',arrC['ClientProfile']['address']);
						$("#form_cart input[id|='txt_b_suburb']").attr('value',arrC['ClientProfile']['suburb']);
						$("#form_cart input[id|='txt_b_state']").attr('value',arrC['ClientProfile']['state']);
						$("#form_cart input[id|='txt_b_postcode']").attr('value',arrC['ClientProfile']['postcode']);
					}
				}
    		});
		} );
	}
}

function cpBilling(obj) {
	if (obj.checked == true) {
		$("#"+g_form_id+" input[id|='txt_s_company']").attr('value',$("#"+g_form_id+" input[id|='txt_b_company']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_fname']").attr('value',$("#"+g_form_id+" input[id|='txt_b_fname']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_lname']").attr('value',$("#"+g_form_id+" input[id|='txt_b_lname']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_email']").attr('value',$("#"+g_form_id+" input[id|='txt_b_email']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_phone']").attr('value',$("#"+g_form_id+" input[id|='txt_b_phone']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_fax']").attr('value',$("#"+g_form_id+" input[id|='txt_b_fax']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_mobile']").attr('value',$("#"+g_form_id+" input[id|='txt_b_mobile']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_address']").attr('value',$("#"+g_form_id+" input[id|='txt_b_address']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_suburb']").attr('value',$("#"+g_form_id+" input[id|='txt_b_suburb']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_state']").attr('value',$("#"+g_form_id+" input[id|='txt_b_state']").attr('value'));
		$("#"+g_form_id+" input[id|='txt_s_postcode']").attr('value',$("#"+g_form_id+" input[id|='txt_b_postcode']").attr('value'));
	} else {
		$("#"+g_form_id+" input[id|='txt_s_company']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_fname']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_lname']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_email']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_phone']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_fax']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_mobile']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_address']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_suburb']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_state']").attr('value','');
		$("#"+g_form_id+" input[id|='txt_s_postcode']").attr('value','');
	}
}

function docPrint(areaId) { 
	var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
    disp_setting+="scrollbars=yes,width=760,height=600,left=100,top=25,resizable=1"; 
  	var content_vlue = document.getElementById(areaId).innerHTML; 
  
  	var docprint=window.open("","",disp_setting); 
   	docprint.document.open(); 
   	docprint.document.write('<html><head><title>CMS Console</title>');
   	docprint.document.write("<link rel='stylesheet' type='text/css' href='css/cms.css' />");
   	docprint.document.write('</head><body style="background:#ffffff;margin-left:2px;" onLoad="self.print()"><center>');          
   	docprint.document.write(content_vlue);          
   	docprint.document.write('</center></body></html>'); 
   	docprint.document.close(); 
   	docprint.focus(); 
}

function trim(txt) {
	while (txt.substring(0,1) == ' ')
		txt = txt.substring(1, txt.length);
	while (txt.substring(txt.length-1,txt.length) == ' ')
		txt = txt.substring(0, txt.length-1);

	return txt;
}
function runForm(act,contentId,txtId,tg) {
	var txtContent = document.getElementById(contentId).innerHTML;
	document.getElementById(txtId).innerHTML = "test";
	document.form_email.action = act;
	document.form_email.target = tg;
	document.form_email.submit();
	//document.getElementById(txtId).innerHTML = '';
}

/**********************************************************************************/
function login() {
	$("#Form_UserLogin").find("#email").focus();
	$("#Form_UserLogin").find("#email").select();
	$("#Login").click(function() {
		requestLogin();
	});
	$("#Form_UserLogin").keydown(function(ev) {
		if (ev.keyCode == 13) {
			requestLogin();
		}
	});
}
function requestLogin() {
	var params = $("#Form_UserLogin").serialize();
	var options = {
		type: 'POST',
		data: params,
		dataType: 'json',
		url: g_site_root + '/admin/users/login',
		beforeSend: function() {
			$("#login_error").html("<div class='progress_icon'></div>");
		},
		success: function(data) {
			if (data.success == true) {
				document.location.href = data.redirect;
			} else {
				$("#login_error").html("Login Failed. Email or password is not correct.");
			}
		}
	};
	
	$.ajax(options);
}

function showFields(sid, className, initDisplay) {
	if (initDisplay == true) {
		$("." + className).show();
		if ($("#" + sid).children('input').length == 0) {
			$("#" + sid).append('<input type="hidden" name="data[change_psw]" value="1" />');
		} else {
			$("#" + sid).children('input').val(1);
		}
		
		$("#lnk_expand").html('-');
	}
	
	$("#" + sid).click(function() {
		$("." + className).toggle();
		if ($("#" + sid).children('input').length == 0) {
			$("#" + sid).append('<input type="hidden" name="data[change_psw]" value="1" />');
			$("#lnk_expand").html('-');
		} else {
			$("#" + sid).children('input').remove();
			$("#lnk_expand").html('+');
		}
	});
}

function afterPageLoaded() {
	$("#main_body").splitpanel.refreshHeight(600);
}

function listReturnItems(data, dataType) {
	var prod = '';
	if (dataType == 'json') {
		data = $.parseJSON(data);
	}

	$.each(data.Product, function(i, val) {
		var img_src= "/img/home/noimage_small.gif";
		var disableField = 'disabled';
		var checkedField = '';
		var total_refund = '';
		var returned_qty = '';
		var returned_id  = '';
		$.each(data.ReturnsProduct, function(k, rtn) {
			if (val['id'] == rtn.product_id) {
				disableField = '';
				checkedField = 'checked';
				total_refund = rtn.total_refund;
				returned_qty = rtn.quantity;
				returned_id  = rtn.id;
				
				return false;
			}
		});
		
		if (val['Image'][0] != null) {
		  	img_src= "/img/images/product/" +
					 val['Image'][0].id + 
					 "/" + val['Image'][0].id +
					 "a4" + val['Image'][0].extension;
		}
		
		prod += '<tr>'
			 +  '<td><img src="' + img_src + '" width="35" height="32"></td>'
			 +  '<td class="return_title">&nbsp;Item Number:</td>' 
			 +	'<td class="return_title">' + val.serial_no + '</td>'
			 +	'<td width="20px">&nbsp;</td>'
			 +  '<td>Return Quantity:</td><td>'
			 +	'<select class="return_field"	style="width:50px;" ' + disableField
			 +	' name="data[ReturnsProduct][' + i + '][quantity]">';
					
		for(var j=val['OrdersProduct']['quantity']; j>=1; j--) {
			prod += '<option value="' + j + '" ' 
				 +	(j == returned_qty ? 'selected' : '') 
				 + '>' + j + '</option>';
		}
		prod +=	'</select>'
			 +	'</td>'
			 +  '<td>&nbsp;&nbsp;Total Refund:</td><td>'
			 +	'<input type="text"	class="return_field" style="width:80px;" ' + disableField 
			 +	' name="data[ReturnsProduct][' + i + '][total_refund]" ' 
			 +	' value="' + total_refund + '" >'
			 +	'</td><td>' 
			 +	'<input type="checkbox"	value="' + val.id + '" ' + checkedField
			 +	' name="data[ReturnsProduct][' + i + '][product_id]">'
			 +	'<input type="hidden"	value="' + val.OrdersProduct.order_id + '"'
			 +	'	class="return_field" ' + disableField
			 +	' name="data[ReturnsProduct][' + i + '][order_id]">'
			 +	'<input type="hidden"	value="' + returned_id + '"'
			 +	'	class="return_field" ' + disableField
			 +	' name="data[ReturnsProduct][' + i + '][id]">'
			 +	'</td>'
			 +	'</tr>';
	});
	
	$("#return_items").html(prod);
				
	$("#return_items input[type|=checkbox]").click(function(e) {
		if ($(this).attr('checked') == true) {
			$(this).parent().parent().find('.return_field').attr('disabled', false);
		} else {
			$(this).parent().parent().find('.return_field').attr('disabled', true);
		}
	});
}

function makeNeatUrl (strName) {
	if (strName) {
		var aliasSearch  = [/\&+/g, /\s+/g],
			aliasReplace = [' and ', '-'],
			maxLen = aliasSearch.length;
		for(var i=0; i<maxLen; i++) {
			strName = strName.replace(aliasSearch[i], aliasReplace[i]);
		}
	
		strName = strName.toLowerCase();
	
		return strName;
	}
	
	return false;
}
function loadProductAlias() {
	var $prod_name = $("#product_name").val();
	var $supp_id   = $("#supplier").val();
	var $prod_id   = $("#product_id").val();
	var $params = "prod_name=" + escape($prod_name) + 
								"&supp_id=" + $supp_id + "&prod_id=" + $prod_id;
	var $opts = {
		type: 'POST',
		url: g_site_root + '/admin/products/alias',
		data: $params,
		dataType: 'json',
		beforeSend: function() {},
		success: function(data) {
			if (data.success === true) {
				$("#product_alias").val(data.value);
				$("#product_serial").val(data.serial);
			} else alert("Errors: " + data.error);
		}
	};
	$.ajax($opts);
}
function handleProductAlias() {
	$("#product_name").on('blur' ,function() {
		loadProductAlias();
	});
	
	$("#supplier").on('change', function() {
		loadProductAlias();
	});
}

/****Ajax Pagination****/
function handleSortColumn() {
	var $col = $("#" + g_list_id + " .column, #" + g_list_id + " .pagination");
	var $pg = $("#" + g_list_id + " .page_info");
	if ($col && $col.length > 0) {
		$col.find('a').click(function(e) {
			e.preventDefault();
			var $url = $(this).attr('href');
			var $key = $("#keywords");
			var strData = "";
			if ($key.length > 0 && $.trim($key.val()) != '') strData='keywords=' + $key.val();
			$.ajax ( { 
				type: "POST",
				url: $url,
				data: strData,
				beforeSend: function() {
					$pg.append("<div id='loading_icon'></div>");
				},
				success:function(msg){
					//$("#" + g_list_id).replaceWith(msg);
					display (msg, g_body_id);
    			}
			});
		});
	}
}

/****Html Editor*****/
function showEditor (url,id) {
	var width = 700;
	var height = 600;
 	window.open(url+"?id="+id,"editor","resizable=1,status=0,width="+width+",height="+height);
}

/****Auto fill****/
function autoFill(sourceField, targetField) {
	$('#' + sourceField).blur(function(e) {
		var str = $.trim($(this).val());
		
		str = str.toLowerCase();
		
		$('#' + targetField).val(str.replace(/\s/g, "-"));
	});
}

/****Upload Image*****/
var current_media_settings = null;
function setSelectedMedia (mediaSrc, mediaId) {
	if (mediaSrc && current_media_settings) {
		$('#template-popup-box .template-loading-area').show();
		
		$.extend(current_media_settings, {url: mediaSrc, media_id: mediaId});
		
		var $wrapper = $('#' + current_media_settings.wrapper),
			$tplImg = $wrapper.parent().parent().prev('.template-section');
		
		lazyLoadImage({
			parentNode: $wrapper, 
			maxSize: current_media_settings.width, 
			minSize: current_media_settings.height, 
			resample: true,
			imgSrc: g_site_root + current_media_settings.url,
			callback: function(nIndex, img) {
				var imgRelativeSrc = current_media_settings.url,
					imgId = current_media_settings.media_id;
				
				$('#template-popup-box .template-loading-area').hide();
				$tplImg.html('<img src="' + g_site_root + imgRelativeSrc + '" />');
				
				if (current_media_settings.afterImageSelected && $.isFunction(current_media_settings.afterImageSelected)) 
					current_media_settings.afterImageSelected(imgRelativeSrc, imgId);
			}
		});
	}
}
function loadMediaCenter (obj) {
	current_media_settings = $(obj).data('mediaData');
	window.open(g_site_root + '/admin/medias/list', 'popupMedia', 'width=650,height=600');
	
	return false;
}
function setXhrUploadPhoto(opts) {
	//var fileNameField = opts.file_name_field ? opts.file_name_field : 'file_name';
	var photoInfo = opts.upload_info ? opts.upload_info : 'Drag-Drop Your Photo Here';
	var htmlWrapper = '<div id="' + opts.wrapper + '" class="photo-wrapper">' + 
                      '		<div class="photo-main">' + 
					  '			<div class="photo-crop">' + 
					  '				<span class="photo-info">' + photoInfo + '</span>' + 
					  '			</div>' + 
					  '		</div>' +
					  '		<a class="button amber form-button fileinput-button">' + 
                    //  '			<span>Upload Photo</span>' + 
                      '			<!-- The file input field used as target for the file upload widget -->' + 
                      '			<input type="file" name="uploaded_file">' +     
                	  '		</a>' + 
					  
					  (opts.media_center ? '<button class="btn_media_center" ' +
					  '	onclick="return loadMediaCenter(this);">Media Center</button>' : '') +
					  
                     // '		<input type="hidden" value="" class="photo-file-name" name="' + fileNameField + '" />' + 
					  '		<input type="hidden" value="" class="photo-file-src" name="file_src" />' + 
					  '		<div class="fileinput-error"></div>' + 
					  '		<div class="fileinput-progress">' + 
                      '			<span class="fileinput-percent"></span>' + 
                      '			<div class="fileinput-progress-bar"></div>' + 
                   	  '		</div>' +
					  '		<div class="photo-uploading"><img src="/img/icons/icon_load.gif" /></div>' + 
                	  '</div>';
	
	var $parent = typeof(opts.photo_parent) == 'object' ? opts.photo_parent : $('#' + opts.photo_parent);
	$parent.html(htmlWrapper);
	xhrUploadPhoto(opts);
	
	if ($parent.find('#' + opts.wrapper + ' .btn_media_center').length > 0) {
		$parent.find('#' + opts.wrapper + ' .btn_media_center:first').data('mediaData', opts);
	}
	
	if (opts.url) {
		lazyLoadImage({
			parentNode: $('#' + opts.wrapper), 
			maxSize: opts.width, 
			minSize: opts.height, 
			resample: true,
			imgSrc: opts.url
		});
	}
}
function xhrUploadPhoto(opts) {
	var $wrapper = $.type(opts.wrapper) == 'object' ? opts.wrapper : $("#" + opts.wrapper),
		$photoTarget = $(".photo-crop", $wrapper),
		$otherWrappers = opts.otherWrappers ? opts.otherWrappers : '',
		$file = $('input[name=uploaded_file]', $wrapper),
		$error = $('.fileinput-error', $wrapper),
		//$fileName = $('input.photo-file-name', $wrapper),
		$fileSrc = $('input.photo-file-src', $wrapper),
		$progress = $('.fileinput-progress', $wrapper),
		imageFor = opts.imageFor ? opts.imageFor : '',
		w = opts.width ? opts.width : 160,
		h = opts.height ? opts.height : 160;
			   
	$file.fileupload({
		url: g_site_root + '/admin/medias/upload',
		dataType: 'json',
		autoUpload: true,
		acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		maxFileSize: 5000000, // 5 MB
		// Enable image resizing, except for Android and Opera,
		// which actually support image resizing, but fail to
		// send Blob objects via XHR requests:
		//disableImageResize: /Android(?!.*Chrome)|Opera/
		 //   .test(window.navigator.userAgent),
		previewMaxWidth: w,
		previewMaxHeight: h,
		previewCrop: true,
		messages: {  
			acceptFileTypes: 'File type must be one of gif, jpeg/jpg, png',
			maxFileSize: 'File is too large (must be no more than 5MB)',
			minFileSize: 'File is too small'
		}
	}).on('fileuploadadd', function (e, data) {
			   
	}).on('fileuploadprocessalways', function (e, data) {
		var index = data.index,
			file = data.files[index];
		
		$error.empty().hide();
		if (file.preview) {
			$photoTarget.html(file.preview);
			
			//if ($otherWrappers) {
			//	$otherWrappers.each(function(i, t) {
			//		$(t).find('.photo-crop').html(file.preview);
			//	});
			//}
		}
		if (file.error) {
			$error.html(file.error).not(':visible').fadeIn();
		}
	}).on('fileuploadprogressall', function (e, data) {
		/*var progress = parseInt(data.loaded / data.total * 100, 10);
		
		$('.fileinput-percent', $progress).html('Uploading ... <br>' + progress + '%');
		$progress.not(':visible').show();
		$('.fileinput-progress-bar', $progress).css(
			'width',
			progress + '%'
		);*/
	}).on('fileuploadstart', function (e) {
		
		$('.photo-uploading', $wrapper).show();
		
	}).on('fileuploaddone', function (e, data) {
		
		$('.photo-uploading', $wrapper).hide();
		
		var r = data.result;
		if (r.status == 1) {
			var d = r.data,
				photoSrc = d.url + '?uq=' + (new Date()).getTime();
				
				//$fileName.val(d.name);
				$fileSrc.val(d.file);
				
				lazyLoadImage({
					parentNode: $wrapper, 
					maxSize: w, 
					minSize: h, 
					resample: true,
					imgSrc: g_site_root + photoSrc
				});
				
				if (opts.callback && $.isFunction(opts.callback)) opts.callback(d);
				
		} else {
			$error.html(r.errorMsg).not(':visible').fadeIn();
		}
		
		//$('.fileinput-percent', $progress).empty();
		//$('.fileinput-progress-bar', $progress).css({width: 0});
		//$progress.fadeOut();
		/*$.each(data.result.files, function (index, file) {
			if (file.url) {
				var link = $('<a>')
								.attr('target', '_blank')
								.prop('href', file.url);
				$(data.context.children()[index])
					.wrap(link);
			} else if (file.error) {
				var error = $('<span class="text-danger"/>').text(file.error);
				$(data.context.children()[index])
					.append('<br>')
					.append(error);
			}
		});*/
	}).on('fileuploadfail', function (e, data) {
		$('.photo-uploading', $wrapper).hide();
		$error.html('Unknown Error! <br> Please try another photo.').not(':visible').fadeIn();
		//console.log('fail', data.result);
		//$('.fileinput-percent', $progress).empty();
		//$('.fileinput-progress-bar', $progress).css({width: 0});
		//$progress.fadeOut();
		/*$.each(data.files, function (index, file) {
			var error = $('<span class="text-danger"/>').text('File upload failed.');
			$(data.context.children()[index])
				.append('<br>')
				.append(error);
		});*/
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
}
function getPhotoSize(objImg, maxSize, minSize) {
	var w = objImg.width,
		h = objImg.height,
		r = w/h;
		
	if (r > 1) {
		width = maxSize;
		height = maxSize * h / w;
	} else if (r == 1) {
		width = maxSize;
		height = maxSize;
	} else {
		width = minSize;
		height = minSize * h / w;
	}
	
	return {width: width, height: height};
}
function getCropDimension(size, crop) {
	var left = (crop.width - size.width) / 2,
		top  = 0;
	if (crop.height > size.height) {
		top = (crop.height - size.height) /2;
	}
	
	return {left: left, top: top};
}
function lazyLoadImage(opts) {
	$('<img />')
		.attr('src', opts.imgSrc)
		.load({parentNode: opts.parentNode}, function(e){
			var imgSize = {width: opts.maxSize, height: opts.maxSize},
				$node = $('.photo-crop', e.data.parentNode),
				cssValue = {width: imgSize.width + 'px', height: imgSize.height + 'px'},
				className = opts.className ? opts.className : '';
			
			if (opts.resample) {
				imgSize = getPhotoSize(this, opts.maxSize, opts.minSize);
				
				var crop = getCropDimension(imgSize, {width: opts.maxSize, height: opts.maxSize});
				cssValue = {
					width: imgSize.width + 'px', 
					height: imgSize.height + 'px',
					marginTop: crop.top + 'px',
					marginLeft: crop.left + 'px'
				};
			}

			$node.empty();
			$(this)
				.hide()
				.addClass(className)
				.css(cssValue)
				.appendTo($node)
				.fadeIn('normal', function() {
					if (opts.callback && $.isFunction(opts.callback)) opts.callback(opts.currentIndex, this);
				});
		})
		.error({parentNode: opts.parentNode}, function(e) {
			if (opts.backupImages) {
				opts.imgSrc = opts.backupImages;
				opts.backupImages = '';
				lazyLoadImage(opts);
			} else {
				var $node = $('.photo-crop', e.data.parentNode),
					className = opts.className ? opts.className : '',
					errImg = opts.errorImage ? opts.errorImage : '';//'/img/icons/nophoto.jpg';
				
				$(this)
					.addClass(className)
					.attr('src', errImg)
					.fadeIn('normal', function() {
						if (opts.callback && $.isFunction(opts.callback)) opts.callback(opts.currentIndex, this);
					});
			}
		});
}
function addImageToMedeaCenter(data, callback) {
	var params = data.media_id ? {media_id: data.media_id} : {
		file_name: data.name, 
		media_name: data.name, 
		file_src: data.file
	};
	var opts = {
		url: g_site_root + '/admin/medias/saveMedia',
		type: 'POST',
		data: params,
		dataType: 'JSON',
		beforeSend: function() {},
		success: function(jData) {
			if (callback && $.isFunction(callback)) callback(jData);
		}
	};
	
	$.ajax(opts);
}
function setImageAddEvent(btnAddId, listWrapId, opts) {
	var theOptions = {
			info: "Please Upload Image",
			externalLink: false,
			width: 100,
			height: 100
		},
		settings = theOptions;
	
	if (opts) settings = $.extend({}, theOptions, opts);
	
	var onlyOne = settings.only_one_image ? settings.only_one_image : false,
		imgSectionPrefx = btnAddId + '-image-',
		imgWrapPrefix = btnAddId + '-wrap-';
		
	$('#' + btnAddId).unbind('click').click(function(e) {	
		var $this = $(this),
			$parent = $this.parent().parent(),
			$siblings = $parent.siblings('.one-item'),
			num = $siblings.length,
			bid = imgSectionPrefx + num,
			wrapId = imgWrapPrefix + num,
			fieldLink = settings.media_link_name ? settings.media_link_name : btnAddId + '-image-link',
			fieldUrl = settings.media_url_name ? settings.media_url_name : btnAddId + '-image-url',
			fieldImgId = settings.media_id_name ? settings.media_id_name : btnAddId + '-image-id',
			extLinkInputType = settings.externalLink === true ? 'text' : 'hidden',
			oneBanner = '<tr class="one-item"><td>' + 
						//'	<input type="hidden" name="top_banner_id[]" class="home-banner-id" value="" />' + 
						'	<input type="' + extLinkInputType + '" name="' + fieldLink + '[]" class="home-banner-url" value="" placeholder="enter url here" />' +
						'	<div id="' + bid + '" class="home-banner-one image-section"></div>' +
						'	<input type="hidden" name="' + fieldUrl + '[]" class="home-banner-img" value="" />' +
						'	<input type="hidden" name="' + fieldImgId + '[]" class="home-img-id" value="" />' +
						'	<button class="home-banner-del">Delete</button>' +
						'</td></tr>';
		
		if (onlyOne == true) $('#' + btnAddId).hide();
		
		$parent.after(oneBanner);
		setXhrUploadPhoto({
			photo_parent: bid,
			wrapper: wrapId,
			upload_info: settings.info,
			width: settings.width,
			height: settings.height,
			url: '',
			media_center: true,
			callback: function(data) {
				addImageToMedeaCenter(data, function(jData) {
					if (jData.status == 1) {
						var imgUrl = jData.data.url ? jData.data.url : '',
							imgId = jData.data.media_id ? jData.data.media_id : '',
							$bid = $('#' + bid);
						$bid.next('.home-banner-img').val(imgUrl);
						$bid.siblings('.home-img-id:first').val(imgId);
					}
				});
			},
			afterImageSelected: function(imgSrc, imgId) {
				var $bid = $('#' + bid);
				$bid.next('.home-banner-img').val(imgSrc);
				$bid.siblings('.home-img-id:first').val(imgId);
			}
		});
		
		return false;
	});
	
	$('#' + listWrapId).off('click').on('click', 'tr.one-item .home-banner-del', function(e) {
		var $this = $(this),
			$tr = $this.parent().parent();
		
		if (onlyOne == true) $('#' + btnAddId + ':hidden').show();
			
		$tr.remove();
	});
	
	if (settings.medias) {
		var arrMedias = $.parseJSON(settings.medias);
		
		$.each(arrMedias, function(i, m) {
			$('#' + btnAddId).trigger('click');
			
			var imgSectionId = imgSectionPrefx + i,
				$imgSection = $('#' + imgSectionId);

			$('.photo-crop', $imgSection).html('<img src="' + m.media_url + '" />');
			$imgSection.next('input.home-banner-img').val(m.media_url);
			$imgSection.siblings('.home-img-id:first').val(m.media_id);
		});
	}
}

function setPriorityEvent(ctrl, callback) {
	$('.btn_priority').unbind('click').click(function(e) {
		var $this = $(this),
			$row = $this.parent().parent(),
			$moveRow = $row.next(),
			params = {
				action: 'down',
				cat_id: $.trim($('#fn_cat_id').val()),
				item_id: $.trim($row.attr('id'))
			};
		if ($this.hasClass('btn_priority_up')) {
			params.action = 'up';
			$moveRow = $row.prev();
		}
		var opts = {
			url: g_site_root + '/admin/' + ctrl + '/setpriority',
			type: 'POST',
			data: params,
			dataType: 'JSON',
			beforeSend: function() {},
			success: function(jData) {
				if (jData.status == 1) {
					var p1 = $row.children(':nth-child(3)').text(),
						p2 = $moveRow.children(':nth-child(3)').text(),
						$newRow = $moveRow.clone(true);
					
					if (jData.data && jData.data.priority) {
						p2 = jData.data.priority;
					}
					 
					$newRow.children(':nth-child(3)').text(p1);
					$row.children(':nth-child(3)').text(p2);
					
					if (params.action == 'up') {
						$row.after($newRow);
					} else {
						$row.before($newRow);
					}
					$newRow.attr({bgcolor: $row.attr('bgcolor')}); 
					$row.attr({bgcolor: $moveRow.attr('bgcolor')});
					$moveRow.remove();
						
					if (callback && $.isFunction(callback)) callback(jData);
				}
			}
		};
		
		$.ajax(opts);
	});
}

function setupAutoFillup(inputId, enterId) {
	var $input = $('#' + inputId),
		$enter = $('#' + enterId);
	$enter
		.unbind('blur')
		.blur(function(e) {
			var val = $.trim( $(this).val() );
			if (val)  val = makeNeatUrl(val);
		
			$input.val(val);
		})
		.unbind('keyup')
		.keyup(function(e) {
			var val = $.trim( $(this).val() );
			if (val)  val = makeNeatUrl(val);
		
			$input.val(val);
		});
}


function adjustPanelSize() {
	var $right = $('#main_right'),
		$left = $('#main_left'),
		rightHeight = $right.height(),
		leftHeight = $left.height();

	if (rightHeight > leftHeight) $left.css({height: rightHeight});
	else if (leftHeight > rightHeight) $right.css({height: leftHeight});
	
	$('#splitpanel_bar, #splitpanel_fake').css({height: $left.height() });
}

/****Initialize page****/
$(document).ready (function() {
	//$('#' + g_tree_body).jScrollPane({showArrows: false, autoReinitialise: true});
	splitPanel();
	if ($("#Form_UserLogin").length == 0) { //if not login page
		menuLoad();
	}
	login();
	//handleProductAlias();
	handleSearch();
	
	/*handle default sub-product in product details page*/
	$("#table_subproduct input[type|=radio]").on('click', function() {
	  	$("#table_subproduct input.rad_subprod").val(0);
	  	$(this).siblings('input.rad_subprod').val(1);
	});
  
	$(window).resize(function(e) {
  		adjustPanelSize();
  	
	}).scroll(function(e) {
		adjustPanelSize();
	});
});
