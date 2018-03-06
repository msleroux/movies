var flachMsg = $(".alert");

console.log(flachMsg);

if(flachMsg.length>0){
    setTimeout(function(){flachMsg.fadeOut("slow")},3000);
}
