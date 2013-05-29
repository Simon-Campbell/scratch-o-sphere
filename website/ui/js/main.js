// Function to get original image size
function imgRealSize(img) {
	var $img = $(img);
	if ($img.prop('naturalWidth') === undefined) {
		var $tmpImg = $('<img/>').attr('src', $img.attr('src'));
		$img.prop('naturalWidth', $tmpImg[0].width);
		$img.prop('naturalHeight', $tmpImg[0].height);
	};
	return {
		'width': $img.prop('naturalWidth'),
		'height': $img.prop('naturalHeight')
	};
};	

// Displays the variable in the textarea: codeVar
function displayVars(x) {
	$("#codeVar").val(x);
};

// Test if its a number
function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
};

// Generates the code output
var genCode = function() {
	code="";
	$( "#output li" ).each(function() {
		var a = "";
		if ( $(this).children("input").length > 0 ) {
			$( this ).children("input").each(function() {
				var temp = $(this).attr("value")+"";
				if ( temp.length==0  ) {
					temp = "0";
				};
				var test = (temp+",");
				if ( $(this).attr("name")=="ch"  ) {
					test = $(this).css("background-color");
					test = test.slice(4);
				}	;			
				a += test;
			});
		};
		code += ( ($(this).attr("name") + "," + a).slice(0,-1) + "\n" );
	});	
	$("#codeOut").val(code);
};

// Dynamically resizes all images to fit page size
var resizeImg = function() {
	var temp;
	var imgsize = ( $("#tabs").width());
	$(" #bin img,#bin").css( "width",imgsize+"px" );					// resize bin		
	$("#control li img").each(function() {								// resize control
		temp = parseInt(imgRealSize(this).width * (imgsize/280)	)				
		$(this).css("width",temp - 2+ "px")
		$(this).parent().css("width",temp + "px")
	});			
	$("#motion li img").each(function() {								// resize motion
		temp = parseInt(imgRealSize(this).width * (imgsize/297)	)				
		$(this).css("width",temp - 2+ "px")
		$(this).parent().css("width",temp + "px")
	});			
	$("#audio li img").each(function() {								// resize audio
		if((imgsize/130) > 1){
			temp = parseInt(imgRealSize(this).width)
		}
		else{
			temp = parseInt(imgRealSize(this).width * (imgsize/130))
		};			
		$(this).css("width",temp - 2+ "px")
		$(this).parent().css("width",temp + "px")
	});			
	$("#visual li img").each(function() {								// resize visuals
		temp = parseInt(imgRealSize(this).width * (imgsize/289)	)				
		$(this).css("width",temp - 2+ "px")
		$(this).parent().css("width",temp + "px")
	});	
	$("#vars li img").each(function() {									// resize variables
		temp = parseInt(imgRealSize(this).width * (imgsize/320)	)				
		$(this).css("width",temp - 2+ "px")
		$(this).parent().css("width",temp + "px")
	});	
	$("#bin").css("top", $("#tabs").height() + $("#bin").height() + "px");					// re-align bin

	// Resize fonts to fit tabs: can be glitchy
	if($("#item").width() <= $("#twidth").width()&&((parseInt(  $("#item li a").css("font-size")))>3)){
		while($("#item").width() <= $("#twidth").width()){
			$("#item li a").css("font-size", (parseInt(  $("#item li a").css("font-size"))-1));
		}
	}
	else{				
		if($("#item").width() >= $("#twidth").width()+10){$("#item li a").css("font-size", "16px");}				
	};
};

// Main code for page
$(function() {
	var codeVars = "Variables:\n"
	var indexitem;
	var code;
	var ifNeedFormat = false;
	var mult = false;
	var imgHeight = 0;
	
	// The setting of draggables
	$( "#control li, #motion li, #audio li, #visual li,#vars li" ).draggable( {
		
		// What shows when dragging
		helper: function (event) {			
			var helper = $(this).clone();
			helper.css("padding-top","60px");
			helper.css("width","300px");
			helper.children("img").css("width","auto");
			return helper;
		},
		
		// Sets height of placeholder
		start:  function(event){
			imgHeight = 53;
		},				
		cursorAt:{ top: 80 },									// offset for extra padding for beter colision detection
		revert: false,
		connectToSortable: "#output" ,							// list connected to
	});
	
	// The setting of the draggable list
	$( "#output" ).sortable({ 
		
		items:"#control li, #motion li, #audio li, .ui-draggable, #visual li,#vars li", // only allows blocks to be added blockes to be sorted, not start block.
		placeholder: "holder",									// class for css stuff for placeholder
		cancel: ".ui-state-disabled",							// stops the top being sorted
		
		// What is shown when sorting: VERY COMPLICATED!! Do NOT change!
		helper: function( e,item ) {
			var helper = $(item);								// item being dragged
			var testName = helper.attr("name");					// name of item
			imgHeight =0;										// set placeholder height initially to to zero
			
			// Multidrag function for multidrag button and shift click
			if(e.shiftKey||mult) {
				imgHeight =  9;									// extra height for tabby bits
				ifNeedFormat = true;							// for extra formatting needed when dropped
				var countLoop = 1;								// count for stack, used for consistancy with loops and ifs		
				var a = ( $("#output").children().index(item));	// index of item in list
				
				// Sets all items after the dragged one to have a class "selected"
				$.each($("#output li"), function(index, value) {					
					if(index>=a){								// if index of item is larger than dragged then add class

						// Loop and If stacks. Ends if reaches an "end" if there was no matching "start"
						if($(this).attr("name")=="loopFor"){
							countLoop ++;
						};
						if($(this).attr("name")=="ifNe"||$(this).attr("name")=="ifEq"||$(this).attr("name")=="ifGe"||$(this).attr("name")=="ifLe"){
							countLoop += 2;
						};
						if($(this).attr("name")=="endLoop"||$(this).attr("name")=="endIf"||$(this).attr("name")=="else"){
							countLoop --;
						};						
						if(countLoop>0){
							$(this).addClass("selected") ;		// adds the class
							imgHeight +=  44; 					// increases placeholder height
						}										
						else{
							a=100;								// ends loop if unmatched "end"
						};						
					};					
				});
				
				// Gets rid of an unusual problem I cant fix any other way :( and also adds all "selected" to a multidrag helper
				var marginerror = parseFloat(item.css("margin-left"));
				var elements = item.parent().children('.selected').css("margin-left",function( index,value ){return parseFloat($(this).css("margin-left")) - marginerror + "px"}).clone();
				item.data('multidrag', elements).siblings('.selected').remove();				
				helper.append(elements);				
				helper.css("width","auto");				
				return helper;
			};
			
			// Multidrag for loops
			if(testName == "loopFor") {
				ifNeedFormat = true;
				imgHeight =  9;
				var a = ( $("#output").children().index(item));
				var stopLoop = 0;
				$.each($("#output li"), function(index, value) {					
					if(index>=a){
						if($(this).attr("name") == "loopFor"){stopLoop ++};
						if(stopLoop > 0){ $(this).addClass("selected");imgHeight +=  44; }else{a=100};
						if($(this).attr("name") == "endLoop"){stopLoop --};
						if(stopLoop == 0){a=100};						
					};					
				});				
				var marginerror = parseFloat(item.css("margin-left"));
				var elements = item.parent().children('.selected').css("margin-left",function( index,value ){return parseFloat($(this).css("margin-left")) - marginerror + "px"}).clone();
				item.data('multidrag', elements).siblings('.selected').remove();
				helper.append(elements);
				helper.css("width","auto");
				return helper;
			};
			
			// Multidrag for ifs
			if(testName == "ifEq"||testName == "ifNe"||testName == "ifLe"||testName == "ifGe") {
				ifNeedFormat = true;
				imgHeight =  9;
				var countLoop = 0;
				var a = ( $("#output").children().index(item));				
				$.each($("#output li"), function(index, value) {					
					if(index>=a){						
						if($(this).attr("name")=="ifNe"||$(this).attr("name")=="ifEq"||$(this).attr("name")=="ifGe"||$(this).attr("name")=="ifLe"){countLoop += 2};						
						if(countLoop>0){
							$(this).addClass("selected"); 
							imgHeight +=  44;
						}
						else{
							a=100
						};
						if($(this).attr("name")=="endIf"||$(this).attr("name")=="else"){countLoop --};
						if(countLoop == 0){a=100};
					};					
				});				
				var marginerror = parseFloat(item.css("margin-left"));
				var elements = item.parent().children('.selected').css("margin-left",function( index,value ){return parseFloat($(this).css("margin-left")) - marginerror + "px"}).clone();
				item.data('multidrag', elements).siblings('.selected').remove();
				helper.append(elements);
				helper.css("width","auto");
				return helper;
			};
			
			// Normal drag
			imgHeight = 53;
			helper.css("width",$(this).children("img").css("width"));
			return helper;
		},
		
		// Sets the placeholder height
		start: function(e, ui){
			if(imgHeight < 53){
				imgHeight=53;
			};			
			ui.placeholder.height(imgHeight+"px");			
		},
		
		// Sets the placeholder margin to map indentations
		sort: function(e, ui){
				var thing = $("#canvas li").not(".selected,.ui-sortable-helper").eq($("#canvas li").not(".selected,.ui-sortable-helper").index($(".holder"))-1); 				// the item above the placeholder
				var tname = thing.attr("name");																																	// the name atribute		
				if((tname!="loopFor"&&tname!="ifLe"&&tname!="else"&&tname!="ifGe"&&tname!="ifNe"&&tname!="ifEq")||$("#canvas li").index()<=3||thing.css("display")=="none"){	// if it doesent fall below a loop of if/else 
					ui.placeholder.css("margin-left",thing.css("margin-left"));																									// take the margin from "thing"
				}
				else{
					ui.placeholder.css("margin-left",parseFloat(thing.css("margin-left"))+30+"px");																				// else add 30px to "thing's" margin
				};				
		},
		
		// Recalculates all the margins and removes annoying formatting issue from multidrag
		stop: function (e, ui) {
		
			// Removes a bug
			if(ifNeedFormat) {
				ifNeedFormat = false;				
				ui.item.after(ui.item.data('multidrag')).remove();
				$("#output").children().removeClass("selected");
				$.each($("#output li"), function(index, value) {					
					$(this).css("width",$(this).children().width())	;				
				});
			};
			
			// Indents all blocks to the correct place: crazy long if statements i cant be bothered explaining
			$.each($("#output li"), function(index, value) {
				if(index!=0){
					$(this).css("margin-left",parseFloat($("#canvas li").eq(index - 1).css("margin-left"))+"px");
					if(($("#canvas li").eq(index - 1).attr("name")=="loopFor"&&$(this).attr("name")!="endLoop")||(($("#canvas li").eq(index - 1).attr("name")=="ifEq"||$("#canvas li").eq(index - 1).attr("name")=="ifLe"||$("#canvas li").eq(index - 1).attr("name")=="ifGe"||$("#canvas li").eq(index - 1).attr("name")=="ifNe")&&$(this).attr("name")!="else")||($("#canvas li").eq(index - 1).attr("name")=="else"&&$(this).attr("name")!="endIf")){
						if(parseFloat($("#canvas li").eq(index - 1).css("margin-left"))-parseFloat($(this).css("margin-left"))!=30){
							$(this).css("margin-left",parseFloat($("#canvas li").eq(index - 1).css("margin-left")) +30+"px");
						};
					};
					if(($("#canvas li").eq(index - 1).attr("name")!="loopFor"&&$(this).attr("name")=="endLoop")||(($("#canvas li").eq(index - 1).attr("name")!="ifEq"&&$("#canvas li").eq(index - 1).attr("name")!="ifLe"&&$("#canvas li").eq(index - 1).attr("name")!="ifGe"&&$("#canvas li").eq(index - 1).attr("name")!="ifNe")&&$(this).attr("name")=="else")||($("#canvas li").eq(index - 1).attr("name")!="else"&&$(this).attr("name")=="endIf")){
						if(parseFloat($("#canvas li").eq(index - 1).css("margin-left"))-parseFloat($(this).css("margin-left"))!=-30){
							$(this).css("margin-left",parseFloat($("#canvas li").eq(index - 1).css("margin-left")) -30+"px");
						};
					};
				};
			});			
		},	
		
		// Resizes dropped items correctly and also adds the endloop/endIf/else blocks
		receive: function( event, ui ) {			
			$("#canvas").css("min-height",$("#output").height()+460 +"px");                                                        	// resizes canvas
			$(this).data().sortable.currentItem.children("img").css("width","auto");												// sets img width natural				
			$(this).data().sortable.currentItem.css("width",$(this).data().sortable.currentItem.children("img").css("width"));		// sets li width to child img width to stop annoying dragging by invisable extra li part
			
			// Adds endLoop block
			if( ($(ui.item).is("#LoopFor,#LoopWhile")) ) {				
				$("#canvas li").eq(( $("#output").children().index($(this).data().sortable.currentItem))).after($('<li name="endLoop" class="ui-draggable ui-state-disabled" style="display: list-item;width:132px;"><img src="ui/img/sos/EndLoop.png" /></li>'));
				$("#canvas").css("min-height",$("#output").height()+460 +"px");
			};
			
			// Adds else and endIf blocks
			if( ($(ui.item).is("#Ifeq,#Ifne,#Ifle,#Ifge")) ) {				
				$("#canvas li").eq(( $("#output").children().index($(this).data().sortable.currentItem))).after($('<li name="else" class="ui-draggable ui-state-disabled" style="display: list-item;width:160px;"><img src="ui/img/sos/Else1.png" /></li>'));
				$("#canvas li").eq(( $("#output").children().index($(this).data().sortable.currentItem ))+1).after($('<li name="endIf" class="ui-draggable ui-state-disabled" style="display: list-item;width:95px;"><img src="ui/img/sos/EndIf1.png" /></li>'));
				$("#canvas").css("min-height",$("#output").height()+460 +"px");
			};					
		},				
	});
	
	// Sets the output list to be draggable
	$("#output").draggable({
		handle: "#top",																	// only dragged by start block
		containment : "parent",															// confined to canvas		
		
		// Error checking for out of canvas glitch
		stop:  function(event){			
			var y = parseFloat($("#output").css("top"));
			var x = parseFloat($("#output").css("left"));			
			if(x<0){
				$("#output").css("left","0px");
			};
			if(y<0){
				$("#output").css("top","0px");
			};
			if(x> $("#output").parent().width()){
				$("#output").css("left",$("#output").parent().width()-300+"px");
			};
			if(y> $("#output").parent().height()-$("#output").height()){
				$("#output").css("top",$("#output").parent().height()-$("#output").height()+"px");
			};
		},		
	});		
	
	// Sets up bin to be able to delete things
	$("#bin").droppable({	
		accept: "#output li.ui-draggable",												// only accept sorting blocks that come from the canvas
		drop: function( event, ui ) {
			if ($(ui.draggable).attr("name") == "set" && $(ui.draggable).children("input").attr("name") == "var" &&codeVars.indexOf("\n"+$(ui.draggable).children("input").attr("value"))>= 0 &&$(ui.draggable).children("input").attr("value")!= "" ){
				codeVars = codeVars.replace($(ui.draggable).children("input").attr("value")+"\n",""); // deletes variable from list string if need to
			};
			displayVars(codeVars);														// displays variable incase one got deleted
			$( ui.draggable ).remove();													// deletes item
			if($("#canvas").height()>500){												// resizes canvas
				$("#canvas").css("min-height",$("#output").height()+436 +"px");
			};
		},
		 hoverClass: "ui-state-active",													// visual feedback that your over bin
	});
	
	// Sets tabs up using jquery widget
	$( "#tabs" ).tabs();			
   
	// Setts clicking functions up as well as others
	$(document).ready(function(){   
		
		// Shows pointer cursor if over a tab
		$('#item li').mouseover(function() {
			if(!($(this).hasClass('ui-state-active'))){
				$(this).css("cursor","pointer");}else{$(this).css("cursor","default");
			};			
		});
		
		// Gets focus for text inputs as touch devices was buggy		
		$("#output").on("click", " input", function(event){			
			$(this).focus().select();
			if ($(this).parent().attr("name") == "set" && $(this).attr("name") == "var" &&codeVars.indexOf("\n"+$(this).attr("value"))>= 0 &&$(this).attr("value")!= "" ){
				codeVars = codeVars.replace($(this).attr("value")+"\n","");				// deletes variable if needed
			};
		});
		
		// Multidrag click function sets mult to inverse
		$("#abuba").on("click", function(event){			
			mult = !mult;			
			if(mult){$(this).addClass("ui-state-highlight").css("background-color","silver");
			}
			else{
				$(this).removeClass("ui-state-highlight").css("background-color","grey");
			};			
		});
		
		// Reposition bin on tab click
		$("#item li a").on("click",function(event){			
			$("#bin").css("top",$("#tabs").height()+30+"px");
		});
		
		// Made a click anywhere on tab trigger the <a> click function for better usability
		$("#item li").on("click",function(event){
			$("#bin").css("top",$("#tabs").height()+30+"px");
			$(this).css("cursor","default");
			$(this).children("a").click();			
		});
		
		// A whole lot of error checking and other cool stuff like setting the bg color of text box on focusout event
		$("#output").on("focusout","input", function() { 
			var thisName = $(this).parent().attr("name");
			var thisVal = $(this).attr("value");
			if (thisName == "setColour"){$(this).css("background-color",thisVal)};
			if ((thisName == "wait"||thisName == "loopFor"||thisName == "goForwardsFor"||thisName == "goBackwardsFor"||thisName == "set,FLASHSPEED"||thisName == "flashFor"||thisName == "flashRandomFor"||thisName == "turnOnFor" )&& thisVal < 0){$(this).attr("value","0")};
			if (thisName == "set,SPEED" && codeVars.indexOf("SPEED") == -1){codeVars += "SPEED\n" ;displayVars(codeVars)};
			if (thisName == "set,FLASHSPEED" && codeVars.indexOf("FLASHSPEED") == -1){codeVars += "FLASHSPEED\n" ;displayVars(codeVars)};
			if (thisName == "set" && $(this).attr("name") == "var" && codeVars.indexOf(thisVal+"\n") == -1){codeVars += thisVal+"\n" ;displayVars(codeVars)};
			if ( $(this).attr("name") != "var" && $(this).attr("name") != "ch"&& codeVars.indexOf(thisVal+"\n") == -1&& !isNumber(thisVal)){ $(this).attr("value","0")};
			if (thisName == "set,SPEED"&&thisVal>100){$(this).attr("value","100")};
			if (thisName == "set,SPEED"&&thisVal<-100){$(this).attr("value","-100")};
			if ((thisName == "goForwardsFor"||thisName == "goBackwardsFor"||thisName == "wait"||thisName == "turnOnFor"||thisName == "flashRandomFor"||thisName == "loopFor")&&thisVal>255){$(this).attr("value","255")};
			if ((thisName == "goForward"||thisName == "goBackward")&&thisVal>10){$(this).attr("value","10")};
		});
		$("#gencode").on("click", genCode);
	});
}); 

// Resize on window size change and load
$(window).resize(resizeImg);
$(window).load(resizeImg);
