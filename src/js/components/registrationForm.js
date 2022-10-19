const memberSelect = document.querySelector('#membership');
const memberType = document.querySelector('#member-type');
const companyFields = document.querySelectorAll('.form-field-company');
const studentFields = document.querySelectorAll('.form-field-student');

if (memberSelect) {
	memberSelect.addEventListener('change', function () {
		if (+this.value === 226) {
			memberType.value = 'student';
			companyFields.forEach(field => {
				field.style.display = 'none';
			});
			studentFields.forEach(field => {
				field.style.display = '';
			});
		} else {
			memberType.value = 'company';
			companyFields.forEach(field => {
				field.style.display = '';
			});
			studentFields.forEach(field => {
				field.style.display = 'none';
			});
		}
	});
}
