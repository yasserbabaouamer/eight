let menu = document.querySelector("#menu-bars");
let navbar = document.querySelector(".navbar");


// available table of each time option
var tablesOfOption1;
var tablesOfOption2;
var tablesOfOption3;
var tablesOfOption4;
var tablesOfOption5;

if(uid!=-1){
    document.getElementById("loged").style.display = 'block';
    document.getElementById("not-loged").style.display = 'none';
    document.getElementById("username").innerHTML = firstName + " "+lastName;
    document.getElementById("user-email").innerHTML = email;

}else{
    document.getElementById("loged").style.display = 'none';
    document.getElementById("not-loged").style.display = 'block';
}


menu.onclick = () =>{
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');
    document.getElementById('user-card').style.display=  'none'; 
}
window.onscroll = () =>{
    menu.classList.remove('fa-times');
    navbar.classList.remove('active');
    document.getElementById('user-card').style.display=  'none'; 
    if(window.pageYOffset > 100) {
        document.querySelector('.to-top').style.opacity= '1';
        document.querySelector('.to-top').style.bottom=  '1rem';
    }else{
        document.querySelector('.to-top').style.opacity= '0';
        document.querySelector('.to-top').style.bottom=  '0rem';
    }
}
//
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

document.querySelector('#search-icon').onclick = () =>{
    document.querySelector('#search-form').classList.toggle('active');
}
document.querySelector('#close').onclick = () =>{
    document.querySelector('#search-form').classList.remove('active');
}
//

document.getElementById('user-icon').onclick = () =>{
    if (document.getElementById('user-card').style.display === 'none') {
        document.getElementById('user-card').style.display=  'block';
        if(isUserLoggedIn){
            document.getElementById('loged').style.display=  'block'; 
            document.getElementById('not-loged').style.display=  'none'; 
        }else{
            document.getElementById('loged').style.display=  'none'; 
            document.getElementById('not-loged').style.display=  'block'; 
        }
        

    }else{
        document.getElementById('user-card').style.display=  'none'; 
    }
}
document.getElementById('logout-button').onclick = () => {
    window.location.href = "logout.php";
    document.getElementById('loged').style.display=  'none';
    document.getElementById('not-loged').style.display=  'block'; 

}

document.getElementById("login-button").onclick = () => {
    window.location.href = "login.php";
}
document.getElementById("signup-button").onclick = () => {
    window.location.href = "signup.php";
}
//

window.addEventListener('scroll', ()=>{
    
    if(window.pageYOffset > 20) {
    }
})
//

var swiper = new Swiper('.home-slider', {
    speed: 350,
    centeredSlides: true,
    autoplay: {
        delay: 6000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true,
      },
      loop: true,
});
//

var swiper = new Swiper('.review-slider', {
    spaceBetween: 30,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    breakpoints: {
        600: {
            slidesPerView: 1,
        },
        750: {
            slidesPerView: 2,
        },
    },
    loop: true,
});
//

var swiper = new Swiper('.menu-slider', {
    spaceBetween: 10,
    autoplay: {
        delay: 3500,
        disableOnInteraction: false,
    },
    breakpoints: {
        0: {
            slidesPerView: 1,
        },
        560: {
            slidesPerView: 2,
        },
        720: {
            slidesPerView: 3,
        },
    },
    loop: true,
});

//
let bookingDate = document.getElementById("dropdown-date");
let today = new Date().toISOString().split("T")[0];

bookingDate.min = today;
bookingDate.defaultValue = today;
//
var chosenTime;
var chosenDate;
var chosenTable;
var size;
var duration;
var chosenDuration;
let timesArray=[];

document.getElementById("reserveBtn").addEventListener("click", function() {
    if(isUserLoggedIn){
        chosenDate = bookingDate.value.toString();
        chosenTime = document.getElementById("dropdown-time").value;
        chosenTable = document.getElementById("dropdown-table").value.toString();
        size  = parseInt (chosenTable);
        chosenDuration = document.getElementById("dropdown-duration").value;
        
        // parse duration to seconds then add it to chosen time to get end reservation time
        switch(chosenDuration){
            case "30 min":
                duration = 1800;
                break;
            case "01 h":
                duration = 1800*2;
                break;
            case "01h 30min":
                duration  = 1800 *3;
                break;
            case "02 h":
                duration = 1800 *4;
                break;}
        
        // parse chosen time to seconds to get after before timestamps
        var startReservString = chosenDate+" "+chosenTime;
        var dateObj = new Date(startReservString);
        var startReserv = dateObj.getTime() /1000;
        var before15Min = (dateObj.getTime() - 900000 )/1000;
        var before30Min = (dateObj.getTime() - 900000 *2)/1000;
        var after15Min = (dateObj.getTime() + 900000 )/1000;
        var after30Min = (dateObj.getTime() + 900000 *2)/1000;
        console.log(startReserv);
        console.log(startReserv+duration);
        
        timesArray = [before30Min,before15Min,startReserv,after15Min,after30Min];   
        
    
        let viewsArr = [document.getElementById("time-opt1"),document.getElementById("time-opt2"),
                        document.getElementById("time-opt3"),document.getElementById("time-opt4"),
                        document.getElementById("time-opt5")]
    
        tablesOfOption1 = [];
        tablesOfOption2 = [];
        tablesOfOption3 = [];
        tablesOfOption4 = [];
        tablesOfOption5 = [];
        var arrOfOptions = [tablesOfOption1,tablesOfOption2,tablesOfOption3,tablesOfOption4,tablesOfOption5];
       var isThereAvailableOptions = 0;
        for(let i=0;i<5;i++){
            var startReservSeconds = timesArray[i];
            var endReservSeconds = startReservSeconds + duration;
            $.ajax({
                url: "reservation_system/check_available_tables.php",
                method: "POST",
                data: { 
                  date: chosenDate, 
                  startReserv: startReservSeconds, 
                  endReserv : endReservSeconds,
                  size: size
                },
                success: function(result) {
                    var availableTables = JSON.parse(result.trim());
                    console.log(availableTables.length == 0);
                    if(availableTables.length == 0){
                        console.log("option at "+i+" not available")
                        viewsArr[i].disabled = true;
                        viewsArr[i].style.background = "#e0e0e0";
                        viewsArr[i].style.color = "#000";
                        isThereAvailableOptions++;
                        if(isThereAvailableOptions==5){
                            document.getElementById("timeOptions").style.display = 'none';
                            document.getElementById("no-options").style.display = 'block';
                        }else{
                            document.getElementById("timeOptions").style.display = 'flex';
                            document.getElementById("no-options").style.display = 'none';
                        }
                    }else{
                        viewsArr[i].disabled = false;
                        viewsArr[i].style.background = "var(--black)";
                        viewsArr[i].style.color = "#fff";
                        availableTables.forEach(tid => {
                            arrOfOptions[i].push(tid);
                        });
                    }
                },
                error: function(xhr, status, error) {
                  console.log("Error: " + error);
                }
              });
        }   
        
        document.querySelector(".time-options").style.display = "flex";
        
        
        document.getElementById("time-opt1").innerHTML =  new Date(before30Min*1000).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
        document.getElementById("time-opt2").innerHTML =  new Date(before15Min*1000).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
        document.getElementById("time-opt3").innerHTML =  new Date(startReserv*1000).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
        document.getElementById("time-opt4").innerHTML =  new Date(after15Min*1000).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
        document.getElementById("time-opt5").innerHTML =  new Date(after30Min*1000).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    
    }else{
        
            document.getElementById('user-card').style.display=  'block';
            document.getElementById('loged').style.display=  'none'; 
            document.getElementById('not-loged').style.display=  'block'; 
            setTimeout(() => {
                document.getElementById('user-card').style.display=  'none';
            }, 2000);

    }
    });


document.getElementById("time-opt1").addEventListener('click',function(event){
        var confirmCard = document.querySelector('.modal-container');
        confirmCard.setAttribute('button-id',"time-opt1");

        var button = document.getElementById("time-opt1");
        button.style.background = '#1f3e8d';
        document.querySelector('.modal-container').style.display = "block";
        document.querySelector('.reservation-confirmed').style.display = "none";    
        document.querySelector('.reservation-info').style.display = "block";    
        
        chosenTime = button.textContent;
        document.getElementById("modal-table").innerHTML= chosenTable;
        document.getElementById("modal-time").innerHTML= chosenTime;
        document.getElementById("modal-date").innerHTML= chosenDate;
        document.getElementById("modal-duration").innerHTML= chosenDuration;
        
}) 

document.getElementById("time-opt2").addEventListener('click',function(){
    var confirmCard = document.querySelector('.modal-container');
    confirmCard.setAttribute('button-id',"time-opt2");

    var button = document.getElementById("time-opt2");
    button.style.background = '#1f3e8d';
    document.querySelector('.modal-container').style.display = "block";
    document.querySelector('.reservation-confirmed').style.display = "none";    
    document.querySelector('.reservation-info').style.display = "block";    
    
    
    chosenTime = button.textContent;
    document.getElementById("modal-table").innerHTML= chosenTable;
    document.getElementById("modal-time").innerHTML= chosenTime;
    document.getElementById("modal-date").innerHTML= chosenDate;
    document.getElementById("modal-duration").innerHTML= chosenDuration;
    
})
document.getElementById("time-opt3").addEventListener('click',function(){
    var confirmCard = document.querySelector('.modal-container');
    confirmCard.setAttribute('button-id',"time-opt3");

    var button = document.getElementById("time-opt3");
    button.style.background = '#1f3e8d';
    document.querySelector('.modal-container').style.display = "block";
    document.querySelector('.reservation-confirmed').style.display = "none";    
    document.querySelector('.reservation-info').style.display = "block";    
    
    
    chosenTime = button.textContent;
    document.getElementById("modal-table").innerHTML= chosenTable;
    document.getElementById("modal-time").innerHTML= chosenTime;
    document.getElementById("modal-date").innerHTML= chosenDate;
    document.getElementById("modal-duration").innerHTML= chosenDuration;
    
})
document.getElementById("time-opt4").addEventListener('click',function(){
    var confirmCard = document.querySelector('.modal-container');
    confirmCard.setAttribute('button-id',"time-opt4");

    var button = document.getElementById("time-opt4");
    button.style.background = '#1f3e8d';
    document.querySelector('.modal-container').style.display = "block";
    document.querySelector('.reservation-confirmed').style.display = "none";    
    document.querySelector('.reservation-info').style.display = "block";    
    
    
    chosenTime = button.textContent;
    document.getElementById("modal-table").innerHTML= chosenTable;
    document.getElementById("modal-time").innerHTML= chosenTime;
    document.getElementById("modal-date").innerHTML= chosenDate;
    document.getElementById("modal-duration").innerHTML= chosenDuration;
    
})

document.getElementById("time-opt5").addEventListener('click',function(){
    console.log(uid);
    var confirmCard = document.querySelector('.modal-container');
    confirmCard.setAttribute('button-id',"time-opt5");
    var button = document.getElementById("time-opt5");
    button.style.background = '#1f3e8d';
    document.querySelector('.modal-container').style.display = "block";
    document.querySelector('.reservation-confirmed').style.display = "none";    
    document.querySelector('.reservation-info').style.display = "block";    
    
    
    chosenTime = button.textContent;
    document.getElementById("modal-table").innerHTML= chosenTable;
    document.getElementById("modal-time").innerHTML= chosenTime;
    document.getElementById("modal-date").innerHTML= chosenDate;
    document.getElementById("modal-duration").innerHTML= chosenDuration;
    
})

document.getElementById('close-button').onclick = () =>{
    document.querySelector('.modal-container').style.display = "none";
}
document.getElementById('confirm-button').onclick = () =>{
    // get the clicked option
    var confirmCard = document.querySelector('.modal-container');
    var clickedOptionID = confirmCard.getAttribute('button-id');
    var reservedTableID = -1;
    var startReservation;
    switch(clickedOptionID){
        case "time-opt1" :
            reservedTableID = tablesOfOption1[0];
            startReservation = timesArray[0];
            break;

        case "time-opt2":
            reservedTableID = tablesOfOption2[0];
            startReservation = timesArray[1];
            break;

        case "time-opt3":
            reservedTableID = tablesOfOption3[0];
            startReservation = timesArray[2];
            break;

        case "time-opt4":
            reservedTableID = tablesOfOption4[0];
            startReservation = timesArray[3];
            break;

        case "time-opt5":
            reservedTableID = tablesOfOption5[0];
            startReservation = timesArray[4];
            break;

        default : return;
    }
    var endReservation = startReservation + duration;

            $.ajax({
                url: "reservation_system/make_table_reserv.php",
                method: "POST",
                data: { 
                  uid: uid,
                  tid:reservedTableID, 
                  date:chosenDate,
                  startReserv: startReservation, 
                  endReserv : endReservation,
                },success:function(result){
                    var insertResult = JSON.parse(result);
                    if(insertResult){
                        document.getElementById("timeOptions").style.display = 'none';
                        document.querySelector('.reservation-info').style.display = "none";
                        document.querySelector('.reservation-confirmed').style.display = "block";
                    }
                },error: function(xhr, status, error) {
                    console.log("Error: " + error);
                  }
        })

}

let addToCartButtons  = document.querySelectorAll('.btn');
console.log(addToCartButtons.length);
addToCartButtons.forEach(btn => {

    btn.addEventListener('click',function(event){
        if(uid != -1){
            var mealID = btn.getAttribute("meal-id");
            console.log(mealID);
            $.ajax({
                url : "order_system/add_to_session.php",
                method : "POST",
                data: { 
                mid : mealID
                },
                success:function(result){
                    console.log("result :" + result);
                    event.target.innerHTML = "Added"
                    event.target.disabled = true;
                    event.target.style.background = "#e0e0e0";
                    event.target.style.color = "#000";
                    document.getElementById('notification').style.display = 'flex'
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            }    
            )    
        }else{
            document.getElementById("user-card").style.display = 'block';
            setTimeout(() => {
                document.getElementById('user-card').style.display=  'none';
            }, 2000);
        }
        
    })
});

document.getElementById('add-review').onclick = () =>{
    console.log("add review clicked");
    document.getElementById('add-review').style.display = 'none';
    document.getElementById('write-review').style.display = 'flex';
}

document.getElementById('send-review').onclick= () => {
    if(uid!=-1){
        var review = document.getElementById('textarea').value
        var date = new Date().getTime() /1000
        var rating = 5;
        console.log("sending review")
        console.log("review : "+review);
        console.log("review length :"+review.length);
        if(review.length != 0){
            console.log("inside if statement")
            $.ajax({
                url:"add_review.php",
                method:"POST",
                data:{
                    uid : uid,
                    review:review,
                    rating : rating,
                    date : date
                },success:function(result){
                    console.log("review :" +result);
                    if(result){
                        document.getElementById('textarea').value = "";
                        //document.getElementById('add-review').style.display = 'flex';
                        //document.getElementById('write-review').style.display
                        document.querySelector('.snackbar').style.display = 'flex';
                        setTimeout(() => {
                            document.querySelector('.snackbar').style.display = 'none';
                        }, 2000);
                    }

                },error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }


            })
        }    
    }else{
            document.getElementById('user-card').style.display=  'block';
            document.getElementById('loged').style.display=  'none'; 
            document.getElementById('not-loged').style.display=  'block'; 
            setTimeout(() => {
                document.getElementById('user-card').style.display=  'none';
            }, 2500);
    }
    
}
