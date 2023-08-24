<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style>
    @keyframes rtate {
      from {
        transform: rotate(0deg);
      } to {
        transform: rotate(360deg);
      }
    }
    .loader_ {
      animation: rtate 1s ease infinite;
      width: 25px;
      height: 25px;
      background-color:white;
      border-radius: 50%;
      border: 5px solid #eee;
      border-top: 5px solid #0e84c9;
      box-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
    }
  </style>
</head>
<body>
  <div class="loader_t">

  </div>
</body>
</html>