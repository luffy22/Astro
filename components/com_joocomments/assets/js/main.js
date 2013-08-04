var component_path="";
var shouldDisplay=true;
function initialize(urlComponent){
	component_path=urlComponent;
}
function refreshCaptcha(){
	var captcha_path=component_path+"&task=showcaptcha";
	var imgObj=document.getElementById("captcha_image");
	if(imgObj!=null){
		imgObj.src=captcha_path+'&random='+Math.random();
	}
}
function validateCaptcha(captchaText){
	var isValid=false;
	var parameters=
		"&userCaptcha="+captchaText.value;
	var url=component_path+"&task=checkCaptcha";
	var myRequest = new Request({method: 'post', async:false,url: url,onSuccess: function(responseText){
		if(responseText=="1"){isValid=true;}
    }
});
	myRequest.send(parameters);
	return isValid;
}
function commentWait(){
sp=new Spinner("progress");
sp.show(true);
}

function vote(commentId,type){
	if(shouldDisplay){
	var msg=getMessageObject(4,waitTitle,waitMessage,paImg);
	msg.waiter();
	var url=component_path+"&task=vote";
	var parameters="&comment_id="+commentId+"&type="+type;
	var myRequest = new Request.JSON(
			{method: 'post', 
			async:true,
			url: url,
			onSuccess: function(responseJSON, responseText){
				msg.dismiss();if(responseJSON.vote!=null){
					var likeVal=document.getElementById('jooCommLike'+commentId);
					if(responseJSON.vote>=0){
						likeVal.style.color="#78A8E2";
						likeVal.innerHTML=responseJSON.vote;
					}else{
						likeVal.style.color="#EE845F";
						likeVal.innerHTML=responseJSON.vote;
					}
				}
				getMessageObject(responseJSON.type,responseJSON.title,responseJSON.message,paImg).say();
    }
});
	myRequest.send(parameters);
	}
}

function showEffect(arguments){
	var myObject = JSON.decode(arguments[3]);
		getMessageObject(myObject.type,myObject.title,myObject.message,paImg).say();
	//var myElement = $(document.body);
	//var myFx = new Fx.Scroll(myElement).start(0, 0.5 * document.body.offsetHeight);
}
function getMessageObject(type,titleL,messageL,imgPath){
	//1:success,2:caution,3:error,4:waiting
	var iconImg='okMedium.png';
	if(type!=null && type==''){type=1;}
	if(type==2){iconImg='cautionMedium.png';}
	if(type==4){iconImg='blackWaiter.gif';}
	return new Message({
		iconPath: imgPath,
		icon: iconImg,
		centered: 'true',
		delay: 1000,
		title: titleL,
		message: messageL,
		onShow: fom,
		onComplete: som
	});
}
function fom(){
	shouldDisplay=false;
}
function som(){
	if(this.options.icon=='okMedium.png' || this.options.icon=='cautionMedium.png' ){
	shouldDisplay=true;
	}
}
