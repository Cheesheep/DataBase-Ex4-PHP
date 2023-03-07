
function checkLuxuryList(){
    var type = document.querySelector('input[name="type"]');
    var designer = document.getElementsByName("designer");
    var manu = document.getElementsByName("manufacturer");
    var color = document.getElementsByName("color");
    var price = document.getElementsByName("price_perday");
    if(typeof type != "string" || typeof designer != "string" ||
    typeof manu != "string" || typeof color != "string"){
        return false;
    }
    var Reg = /^\d+(\.\d{1,2})?$/.test(price);//匹配是否只有两位小数
    return Reg;
}
