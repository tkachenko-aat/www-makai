$(".menu-hamb.close").click(function(){
    $(".main-menu").addClass("main-menu-open");
    $(".main-menu").removeClass("main-menu");
});
$(".menu-hamb.open").click(function(){
    $(".main-menu-open").addClass("main-menu");
    $(".main-menu-open").removeClass("main-menu-open");
});