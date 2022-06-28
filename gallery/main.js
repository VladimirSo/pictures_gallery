// console.log('test');

const formEl = document.querySelector('.js-upload-form');
const imgsListEl = document.querySelector('.js-gallery-list');

const getReadableFileSize = (size) => {
  if (size >= 1048576) {
    return (size/1048576).toFixed(2) + ' Mb';
  } else if (size >=10240 && size < 1048576) {
    return (size/1024).toFixed(2) + ' Kb';
  } else if (size < 1024) {
    return (size).toFixed(2) + ' b';
  }
};  

formEl.onsubmit = async (ev) => {
  ev.preventDefault();

  let formData = new FormData(formEl);

  // for(let [name, value] of formData) {
  //   console.log(value);
  // }

  let response = await fetch('/src/upload.php', {
    method: 'POST',
    body:  formData
  });

  let result = Object.values(
    await response.json()
  );
// если с ответом пришла ошибка, показываем ее
  if (result[0] !== '') {
    alert(result[0]);
  } else if (result[0] === '') {
    alert('Успешная загрузка.');
// если успешная загрузка собираем массив с данными картинок которые уже были загружены ранее
    const exImgsArr = [];

    for(let i = 0; i < imgsListEl.children.length; i++ ) {
      const fileName = imgsListEl.children[i].querySelector('img').getAttribute('src').slice(10);
      const fileSize = imgsListEl.children[i].querySelector('.picture__data').textContent.slice(20);

      exImgsArr.push([fileName, fileSize]);
    }
    // console.log(exImgsArr);
/*
забираем из массива с пришедшими данными нужную информацию,
и если одноименных файлов уже не было загружено ранее, то
добавляем их к тем картинкам что уже загружены
*/
    for (let i = 1; i < result.length; i++ ) {
      const fileName = result[i][0];
      const fileSize = getReadableFileSize(result[i][1]);

      if (exImgsArr.some(arr => arr.includes(fileName)) !== true) {
        exImgsArr.push([fileName, fileSize]);
        // сортируем массив
        exImgsArr.sort( (a, b) => {
          const fileNameA = a[0].toLowerCase();
          const fileNameB = b[0].toLowerCase();
          
          if (fileNameA < fileNameB) {
            return -1;
          }
          if (fileNameA > fileNameB) {
            return 1;
          }
          return 0;
        });
      }
    }
    // console.log(exImgsArr);

// удаляем со страницы дом-элементы с ранее загруженными картинками
    if (imgsListEl.children.length > 0) {
      do {
        imgsListEl.children[0].remove();

      } while (imgsListEl.children.length !== 0);
    }

// заново формируем элементы для всех загруженных картинок
    for (let i = 0; i < exImgsArr.length; i++) {
      let fileName = exImgsArr[i][0];
      let fileSize = exImgsArr[i][1];

      imgsListEl.insertAdjacentHTML('beforeend', 
      `<li class="gallery__item picture">
        <div class="picture__wrapper">
          <img class="picture__img" src="../upload/${fileName}" alt="${fileName}"></img>
        </div>
        <span class="picture__data">Размер изображения: ${fileSize}</span>
        <label class="picture__check">Удалить изображение:&nbsp;
          <input type="checkbox" form="deleteImgs" name="delImg${i}" value="${fileName}">
        </label>
      </li>`
      );
    }
  }
  
  formEl.reset();
};