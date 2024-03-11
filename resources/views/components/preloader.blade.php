<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bulbasaur - Pokémon Profile</title>
    <style>
      * {
        box-sizing: border-box;
      }

      #preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: white;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

@keyframes spinball {
        0% {
          top: -500px;
        }
        50% {
          top: 0px;
        }
        75% {
          top: -500px;
        }
        100% {
          top: 0px;
        }
      }

      @keyframes spin {
        0% {
          transform: rotateZ(0deg);
        }
        50% {
          transform: rotateZ(360deg);
        }
        0% {
          transform: rotateZ(0deg);
        }
      }
      .container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      }

      .mainball {
        position: relative;
        width: 400px;
        height: 400px;
        background: #fff;
        border: 25px solid #000;
        border-radius: 50%;
        overflow: hidden;
        animation: spinball 0.5s ease-in-out, spin 3s;
      }

      .mainball::before,
      .mainball::after {
        content: "";
        position: absolute;
      }

      .mainball::before {
        background-color: red;
        width: 100%;
        height: 50%;
      }

      .mainball::after {
        top: calc(50% - 15px);
        width: 100%;
        height: 25px;
        background: #000;
      }

      .pokebutton {
        position: absolute;
        top: calc(50% - 50px);
        left: calc(50% - 50px);
        width: 100px;
        height: 100px;
        background: #7f8c8d;
        /* animation: alternate; */
        border: 20px solid #fff;
        border-radius: 50%;
        z-index: 10;
        box-shadow: 0 0 0 20px #000;
      }
    </style>
  </head>
  <body>
    <div class="preloader">
      <div class="mainball">
        <div class="pokebutton"></div>
      </div>
    </div>
  </body>
</html>
