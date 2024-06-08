<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../static/css/style.css">
    <style>
        body {
    background-image: url("../../static/image/ultralytics_yolov8_image.png");
    background-repeat: no-repeat;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 75vh;
    margin: 0;
    padding-top: 12.5vh;
}

.container {
    background-color: rgba(255, 255, 255, 0.5);
    width: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.text-center {
    font-size: 25px;
    font-weight: bolder;
}

.center {
    display: flex;
    justify-content: center;
    align-items: center;
}

.video {
    width: 47%;
    height: 100vh;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
}
    </style>

</head>

<body id="bod" class="d-flex flex-column justify-content-center align-items-center">

    <div id="main-content" class="mx-auto container d-flex flex-column h-100 justify-content-center">
        <form class="mx-auto" id="mx-auto">
            <div>
                <p class="text-center text-xl-left">Which item would you like to find? </p>
            </div>
            <div class="form-outline mb-4" id="sel">
                <select name="select" id="my_select" class="form-select form-select-lg form-control"
                    aria-label="Large select example">
                    <option disabled selected>Open this select menu</option>

                </select>

            </div>
            <div class="mb-4 center">
                <button id="btn" name="bt" type="button" class="btn-dark btn btn-secondary btn-lg btn-block">Start your
                    search by
                    clicking here!</button>

            </div>

        </form>
    </div>
    <div style="display: none;" id="no">
        <p class="text-center text-xl-left">Searching for... :selectedText </p>
    </div>
    <div class="video" id="camera-container" style="display: none;">
        <video id="video"></video>
    </div>
<script src="https://cdn.jsdelivr.net/npm/opencv4nodejs@5.6.0/lib/opencv4nodejs.min.js"></script>
    <script>
    const wCam = new cv.VideoCapture(0);
    const select = document.getElementById("my_select");


    fetch("/data")
        .then(response => response.json())
        .then(data => {
            data.forEach(item => {
                const option = document.createElement("option");
                option.value = item.id;
                option.textContent = item.Cont;
                select.appendChild(option);
            });
        });

        const openCameraButton = document.getElementById("btn");
        const videoElement = document.getElementById("video");
        openCameraButton.addEventListener("click", async() => {
            document.getElementById('main-content').style.display = 'none';
            document.getElementById('main-content').style.backgroundColor = 'transparent';
            document.getElementById('mx-auto').style.display = 'none';
            document.getElementById('sel').style.display = 'none';
            document.getElementById('my_select').style.display = 'none';
            document.getElementById('no').style.display = 'flex';

            // Now unhide the video element
            document.getElementById('camera-container').style.display = 'flex';
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            videoElement.srcObject = stream;
            await videoElement.play();

            console.log('====================================');
            console.log("player started");
            console.log('====================================');
            setInterval(() => {
                const frame = wCam.read();
                let image = cv.imencode('.jpg',frame).toString('base64');
                fetch('https://ai-stream.future-developers.cloud/predict-image/',{
                    method: 'POST',
                    body:image
                }).then(data=>{
                    console.log(data);
                })
            }, 1000/30);
        });
/////////////////////////////////////////////////////////////////////////////////////////////////////
     // Function to capture selected value on button click
        function captureSelectedOption() {
            event.preventDefault(); // Prevent default form submission

            const selectedOption = document.getElementById('my_select').selectedOptions[0];
            const selectedText = selectedOption.textContent;
            console.log('Selected Value:', selectedText);

        }

        // Event listener for button click
        document.getElementById('btn').addEventListener('click', captureSelectedOption);

        /////////////////////////////////////////////////////////////////////



  </script>


 </body>

</html>
