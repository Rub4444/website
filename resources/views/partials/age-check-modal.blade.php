<!DOCTYPE html>
<html lang="hy">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
  /* Фон и оверлей */
  #ageCheckOverlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
  }
  /* Окно */
  #ageCheckBox {
    background: white;
    padding: 30px 40px;
    border-radius: 8px;
    text-align: center;
    max-width: 320px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
  }
  #ageCheckBox button {
    margin-top: 20px;
    padding: 10px 25px;
    font-size: 16px;
    background-color: #198754;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  #ageCheckBox button:hover {
    background-color: #145c32;
  }
  body.no-scroll {
    overflow: hidden;
  }
</style>
</head>
<body>

    <!-- Оверлей для проверки возраста -->
    <div id="ageCheckOverlay">
        <div id="ageCheckBox">
            <h2>@lang('main.age_check')</h2>
            <p>@lang('main.you_should_be_18')</p>
            <button id="confirmAgeBtn">@lang('main.i_am_18')</button>
        </div>
    </div>

    <script>
    // Запретить прокрутку страницы, пока не подтвердят возраст
    document.body.classList.add('no-scroll');

    document.getElementById('confirmAgeBtn').addEventListener('click', function() {
        document.getElementById('ageCheckOverlay').style.display = 'none';
        document.body.classList.remove('no-scroll');
    });
    </script>

</body>
</html>
