(function() {
  const mainImg = document.getElementById('rgMainImage');
  if (!mainImg) return;

  const thumbs = document.querySelectorAll('.rg-thumb');
  const prevBtn = document.querySelector('.rg-prev');
  const nextBtn = document.querySelector('.rg-next');

  function setActive(index) {
    const target = [...thumbs].find(t => parseInt(t.dataset.index) === index);
    if (!target) return;
    mainImg.src = target.dataset.full;
    mainImg.dataset.index = index;
    thumbs.forEach(t => t.classList.remove('active'));
    target.classList.add('active');
  }

  thumbs.forEach(t => {
    t.addEventListener('click', () => {
      setActive(parseInt(t.dataset.index));
    });
  });

  function move(delta) {
    let current = parseInt(mainImg.dataset.index);
    let total = thumbs.length;
    let next = current + delta;
    if (next < 0) next = total - 1;
    if (next >= total) next = 0;
    setActive(next);
  }

  if (prevBtn) prevBtn.addEventListener('click', () => move(-1));
  if (nextBtn) nextBtn.addEventListener('click', () => move(1));

  // Swipe (móvil)
  let startX = 0;
  mainImg.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, {passive:true});
  mainImg.addEventListener('touchend', e => {
    const diff = e.changedTouches[0].clientX - startX;
    if (Math.abs(diff) > 40) {
      move(diff < 0 ? 1 : -1);
    }
  });
})();