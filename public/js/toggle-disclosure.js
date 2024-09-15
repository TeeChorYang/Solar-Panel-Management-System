function toggleDisclosure(id, button) {
  const disclosure = document.getElementById(id);
  const isExpanded = disclosure.classList.toggle('hidden');
  button.setAttribute('aria-expanded', !isExpanded);

  const plusIcon = button.querySelector('.plus-icon');
  const minusIcon = button.querySelector('.minus-icon');

  if (isExpanded) {
      plusIcon.classList.remove('hidden');
      minusIcon.classList.add('hidden');
  } else {
      plusIcon.classList.add('hidden');
      minusIcon.classList.remove('hidden');
  }
}