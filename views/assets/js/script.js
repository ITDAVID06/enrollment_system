document.addEventListener('DOMContentLoaded', function() {
    const text = "Welcome to the College of Computer Studies (CCS)";
    let index = 0;
    const speed = 80;
    const animatedText = document.querySelector(".animated-text");

    function typeText() {
        if (index < text.length) {
            animatedText.textContent += text.charAt(index);
            index++;
            setTimeout(typeText, speed);
        }
    }
    
    typeText();
});
