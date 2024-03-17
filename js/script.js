
    console.log("JavaScript is running");
    
    var path = window.location.pathname;
    var page = path.split("/").pop();
    console.log("Path:", path);
    console.log("Page:", page);
    var links = document.querySelectorAll('.nav a');


    links.forEach(function(link) {
        console.log(link.getAttribute('href'));
        if (link.getAttribute('href') === page) {
            link.classList.add('active');
            link.classList.add('disabled');
        }
    });