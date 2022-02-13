function ClientEquationCheck(){
	var self=this;
	self.errorMsg="";
	self.check=function(v){
		if (v==""){
			self.errorMsg="Equation string is required.";
			return false;
		}
		return true;
	}
	
}
function CalculatorClient() {
	var self=this;
	self.newEquation=function(){
		document.getElementById("fcalculator").reset();
		document.getElementById("fresult").classList.add("hide");
		document.getElementById("fcalculator").classList.remove("hide");
		document.getElementById("fequation").focus();
	}
	self.retryEquation=function(){
		document.getElementById("fresult").classList.add("hide");
		document.getElementById("fcalculator").classList.remove("hide");
		document.getElementById("fequation").focus();		
	}
	self.submitEquation=function(){
		var serverClient=new restApi();
		var equationString=document.getElementById("fequation").value;
		var eqCheck=new ClientEquationCheck();
		if (eqCheck.check(equationString)){
			serverClient.get("server/calculator/calculatorApi.php?fequation="+encodeURIComponent(equationString), "", function(d){
				if (d.updates.status=="Done"){
					document.getElementById("fanswer").innerText=d.updates.result;
					document.getElementById("fshowequation").innerText=equationString;
					document.getElementById("fshowstatus").innerText=d.updates.status;
					document.getElementById("fshowstatus").classList.add("text-success");
					document.getElementById("fshowstatus").classList.remove("text-error");					
					document.getElementById("fresult").classList.remove("hide");
					document.getElementById("fcalculator").classList.add("hide");
					document.getElementById("fnewbutton").classList.remove("hide");
					document.getElementById("fretrybutton").classList.add("hide");
				}else if (d.updates.status=="Error"){
					document.getElementById("fanswer").innerText=d.updates.result;
					document.getElementById("fshowequation").innerText=equationString;
					document.getElementById("fshowstatus").innerText=d.updates.status;
					document.getElementById("fshowstatus").classList.remove("text-success");
					document.getElementById("fshowstatus").classList.add("text-error");
					document.getElementById("fresult").classList.remove("hide");
					document.getElementById("fcalculator").classList.add("hide");	
					document.getElementById("fnewbutton").classList.add("hide");
					document.getElementById("fretrybutton").classList.remove("hide");					
				}
				
			});
		}else{
			document.getElementById("fmsgbox").classList.remove("hide");
			document.getElementById("fmsg").innerText=eqCheck.errorMsg;
		}
		return false;
	}
}
var calculatorClient=new CalculatorClient();