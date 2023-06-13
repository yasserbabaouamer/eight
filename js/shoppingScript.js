document.querySelectorAll('.navbar a').forEach(a => {
      
    a.addEventListener('mouseover', ()=> {
        document.querySelectorAll('.navbar a').forEach(a => a.classList.remove('active'))
        a.classList.toggle('active')
    })
});

document.getElementsByTagName('header')[0].addEventListener('mouseleave', ()=> {
    document.querySelectorAll('.navbar a').forEach(a => a.classList.remove('active'))
    document.querySelector('.navbar a').classList.toggle('active')
})


document.querySelectorAll('.navbar a').forEach(function(a) {
    a.addEventListener('mouseover', ()=> {
        document.querySelectorAll('.navbar a').forEach(a => a.classList.remove('active'))
        a.classList.toggle('active')
    })  
});
document.getElementById('user-icon').onclick = () =>{
    if (document.getElementById('user-card').style.display=== 'none') {
        document.getElementById('user-card').style.display=  'block';
        document.getElementById('loged').style.display=  'block'; 
        document.getElementById('not-loged').style.display=  'none'; 

    }else{
        document.getElementById('user-card').style.display=  'none'; 
    }
}
document.getElementById('logout-button').onclick = () =>{
    document.getElementById('loged').style.display=  'none'; 
    document.getElementById('not-loged').style.display=  'block'; 
}

var cartItems = document.querySelectorAll('.item');
var totalDiv = document.getElementById('total');
var total = 0;
cartItems.forEach(cart => {
    var plusBtn= cart.children[1].children[1].children[0];
    var displayQte= cart.children[1].children[1].children[1];
    var minusBtn= cart.children[1].children[1].children[2];
    var closeBtn = cart.children[0];
    var priceDiv = cart.children[2].children[0];
    var mealPrice = parseFloat (plusBtn.getAttribute('meal-price'));
    total = total + mealPrice;
    totalDiv.innerHTML = total.toFixed(2)+ " $";
    var mealID = plusBtn.getAttribute('meal-id');
    plusBtn.onclick = () => {
        console.log(mealID);
        var qte = parseInt(displayQte.innerHTML);
        total = total + mealPrice;
        totalDiv.innerHTML = total.toFixed(2) + " $";
        qte++;
        displayQte.innerHTML = qte;
        var newPrice = mealPrice * qte;
        priceDiv.textContent = newPrice.toFixed(2)+" $";
       }

    minusBtn.onclick = () =>{
        var qte = parseInt(displayQte.innerHTML);
        if(qte>1){
            qte--;
            displayQte.innerHTML = qte;
            total = total - mealPrice;
            totalDiv.innerHTML = total.toFixed(2) + " $";
            var newPrice = mealPrice * qte;
            priceDiv.textContent = newPrice.toFixed(2)+" $";
        }
        
    }

    closeBtn.onclick = () =>{
        $.ajax({
            url:"order_system/remove_from_card.php",
            method:"POST",
            data : {
                mid:mealID
            },success:function(result){
                var qte = parseInt(displayQte.innerHTML);
                var totalMeal = mealPrice *qte;
                total = total - totalMeal;
                totalDiv.textContent = total.toFixed(2)+" $";
                cart.remove();
            },error:function(){

            }
        })
        cart.remove();
    }
});

document.getElementById("checkout-button").onclick = () => {
    if(uid==-1){
        alert("You have to be a logged in first !");
        return;
    }
    var cartItems = document.querySelectorAll('.item');
    if(cartItems.length === 0){
        alert("Add items to your card first !");
        return;
    }
    var totalDiv = document.getElementById('total');
    var arrMeals = [];
    cartItems.forEach(cart => {
        var plusBtn= cart.children[1].children[1].children[0];
        var displayQte= cart.children[1].children[1].children[1];
        var mealID = plusBtn.getAttribute('meal-id');
        var quantity = parseInt(displayQte.innerHTML);
        arrMeals.push({mid:mealID,qte:quantity})
    });
    var json = JSON.stringify(arrMeals);
    var total = parseFloat(totalDiv.innerHTML).toFixed(2);
    $.ajax(
        {
            url: "order_system/make_order.php",
            method: "POST",
            data : {
                uid : uid,
                date : new Date().getTime()/1000,
                total : total,
                latitude : 0.0,
                longitude : 1.0,
                arrMeals : json
            }
            ,success:function(result){
                console.log("result :" +result);
                // clear card session
                $.ajax(
                    {
                        url : "order_system/clear_card_session.php",
                        method : "POST",
                        data:{
                            
                        },success:function(result){
                            document.querySelector('.modal-checkout-container').style.display = 'block';
                        },error:function(){
            
                        }
                    }
                )
            }
            ,error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        }
    ) 
}

document.getElementById('modal-confirm-button').onclick = () =>{
    window.location.href= "index.php";
}


