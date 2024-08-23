document.addEventListener('DOMContentLoaded', () => {
    const paragraphs = document.querySelectorAll('.about-text p');
    paragraphs.forEach(paragraph => {
      adjustTextHeight(paragraph);
    });
  });

  function adjustTextHeight(element) {
    const container = document.querySelector('.about-text');
    const maxHeight = container.offsetHeight;
    let currentHeight = element.offsetHeight;
    let lineHeight = parseFloat(window.getComputedStyle(element).lineHeight);

    while (currentHeight < maxHeight && lineHeight < 100) { 
      lineHeight += 1;
      element.style.lineHeight = lineHeight + 'px';
      currentHeight = element.offsetHeight;

      if (currentHeight >= maxHeight) {
        break;
      }
    }
  }