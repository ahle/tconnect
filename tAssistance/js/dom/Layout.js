tAssistance.dom.TBTLayout = function(id){
	
	var container = document.createElement("div");
	container.setAttribute("class","panel");
		
	var title = document.createElement("p");
	title.setAttribute("name","title");
	title.innerHTML = "Listing";	
	
	var buttons = document.createElement("div");
	buttons.setAttribute("name","buttons");
	
	var object = document.createElement("div");
	object.setAttribute("name","object");
		
	container.appendChild(title);
	container.appendChild(buttons);
	container.appendChild(object);
	
	return container;
};

tAssistance.dom.PanelLayout = function(id){
	
	var panel = document.createElement("div");
	panel.setAttribute("class","panel panel-primary");
	panel.setAttribute("style","display:block");
	
	var panel_heading = document.createElement("div");
	panel_heading.setAttribute("class","panel-heading");
	panel_heading.setAttribute("style","padding: 0px; margin: 0px;");
	
	panel.appendChild(panel_heading);
		
	var panel_title = document.createElement("div");
	panel_title.setAttribute("class","panel-title");
	panel_title.setAttribute("style","padding: 5px; margin: 0px;  font-size: 14px");
	panel_title.innerHTML = "Condition Setting";
	
	panel_heading.appendChild(panel_title);
	
	var panel_body = document.createElement("div");
	panel_body.setAttribute("class","panel-body");
		
	panel.appendChild(panel_body);
	
	return panel;
};

tAssistance.dom.Gird4Layout = function(id){
	
	var panel = document.createElement("div");
	panel.setAttribute("class","container-fluid");
	
	var top = document.createElement("div");
	top.setAttribute("class","row");
	
	var bot = document.createElement("div");
	bot.setAttribute("class","row");
		
	panel.appendChild(top);
	panel.appendChild(bot);
		
	var left1 = document.createElement("div");
	left1.setAttribute("name","top-left");
	left1.setAttribute("class","col-md-6");
	
	var right1 = document.createElement("div");
	right1.setAttribute("name","top-right");
	right1.setAttribute("class","col-md-6");
	
	top.appendChild(left1);
	top.appendChild(right1);
	
	var left2 = document.createElement("div");
	left2.setAttribute("name","bot-left");
	left2.setAttribute("class","col-md-6");
	
	var right2 = document.createElement("div");
	right2.setAttribute("name","bot-right");
	right2.setAttribute("class","col-md-6");
	
	bot.appendChild(left2);
	bot.appendChild(right2);
	
	
	return panel;
};

tAssistance.dom.SidebarLayout = function(id){
	
	var panel = document.createElement("div");
	panel.setAttribute("class","container-fluid");
	panel.setAttribute("style","border: 1px dotted black");
	
	var row = document.createElement("div");
	row.setAttribute("class","row");
	
	panel.appendChild(row);
		
	var left = document.createElement("div");
	left.setAttribute("name","left");
	left.setAttribute("class","col-md-2");
	left.setAttribute("style","border: 1px dotted black");
	
	var right = document.createElement("div");
	right.setAttribute("name","right");
	right.setAttribute("class","col-md-8");
	right.setAttribute("style","border: 1px dotted black");
	
	row.appendChild(left);
	row.appendChild(right);
		
	return panel;
};