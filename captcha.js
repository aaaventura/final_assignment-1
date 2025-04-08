function generateCaptcha() {
    console.log("captcha generated")
    const characters = 'ABDEFGHJKLMNPQRTVWYabdefghijkmnpqrtvwy23456789';
    let captcha = '';
    for (let i = 0; i < 6; i++) {
        captcha += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return captcha;
}


function drawCaptcha(captcha) {


    console.log("captcha drawn");


    const canvas = document.getElementById('captcha');
    const ctx = canvas.getContext('2d');
    ctx.font = '30px courier';
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    
    for (let i = 0; i < 1000; i++) {
        ctx.fillRect(Math.random() * canvas.width, Math.random() * canvas.height, 2, 2);
    }


   
    const maxCharWidth = canvas.width / captcha.length;

   
    const x = Math.random() * (canvas.width - maxCharWidth * captcha.length); 
    const y = Math.random() * (canvas.height - 30) + 30; 



    for (let i = 0; i < captcha.length; i++) {
        const char = captcha[i];
        const charX = x + i * maxCharWidth + 10; 
        const charY = y;

        ctx.save(); 
        
        const angle = Math.random() * 0.2 - 0.1; 
        const scale = 1 + Math.random() * 0.3 - 0.15; 

        ctx.translate(charX, charY);
        ctx.rotate(angle);
        ctx.scale(scale, scale);

        ctx.fillStyle = '#5c533e';
        ctx.fillText(char, 0, 0);
        ctx.strokeStyle = '#5c533e'; 
        ctx.lineWidth = 2;
        ctx.strokeText(char, 0, 0);

        ctx.restore(); 
    }


}






function load(){
    console.log("load function called");
    

    const captcha = generateCaptcha();
    drawCaptcha(captcha);



    const form = document.querySelector('form');
    console.log(form);




    form.addEventListener('submit', function(event) {

        console.log("submit button hit");
    
        const userInput = document.getElementById('textBox').value;
    
        // if captcha fails
        if (userInput !== captcha) {
            console.log("if statement hit");
            event.preventDefault();

            alert('Incorrect CAPTCHA. Please try again.');
        }
    });

}


document.addEventListener("DOMContentLoaded", load, false);


