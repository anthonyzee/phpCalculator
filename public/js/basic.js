function restApi() {
	var self=this;
	var reqObj=function(pUrl, pData, pCb){
			var xmlhttp=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function(){
					if (xmlhttp.readyState==4){
							if (xmlhttp.status==200){
									var data=xmlhttp.responseText;
									if (data.charAt(0)=="{" || data.charAt(0)=="["){
										var jsonData=JSON.parse(xmlhttp.responseText);
										pCb(jsonData);	
									}else{
										pCb();
									}
									
							}else{
									pCb();
							}
					}
			}
			this.get=function(){
					xmlhttp.open("GET", pUrl, true);
					xmlhttp.send();
			}
			this.post=function(){
					xmlhttp.open("POST", pUrl, true);
					xmlhttp.setRequestHeader("Content-type","application/json");
					xmlhttp.send(pData);
			}
			
	}
	self.get=function(pUrl, pData, pCb){
		var req=new reqObj(pUrl, pData, pCb);
		req.get();
	}
	self.post=function(pUrl, pData, pCb){
		var req=new reqObj(pUrl, pData, pCb);
		req.post();
	}
}