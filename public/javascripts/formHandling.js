document.addEventListener('DOMContentLoaded', async function(event) {
    let formMode = 'idInput';

    function getRank(payload) {
        fetch('../php/rank.php', {method: 'POST', body: payload})
            .then((response) => {
                return response.json();
            })
            .then(data => {
                document.getElementById('oldRank').textContent = data.oldRank.name;
                document.getElementById('newRank').textContent = data.newRank.name;

                document.getElementById('oldEpaulette').src = data.oldRank.epaulette;
                document.getElementById('newEpaulette').src = data.newRank.epaulette;
            });
    }

    const inputs = document.querySelectorAll('input');
    function manualInputValidation() {
        let inputsValid = true

        inputs.forEach(input => {
            if (input.name != 'pilotId') {
                const value = input.value;
                let success = true;

                try {
                    parseFloat(value);
                } catch(error) {
                    inputsValid = false
                    success = false;
                    input.classList.remove('is-success');
                    input.classList.add('is-danger');
                }

                if (value.length === 0) {
                    inputsValid = false
                    input.classList.remove('is-success');
                    input.classList.remove('is-danger');
                } else if (success) {
                    input.classList.remove('is-danger');
                    input.classList.add('is-success');
                }
            }
        });

        if (inputsValid) {
            const payload = new FormData();
    
            payload.append('hours', document.getElementById('hours').value);
            payload.append('points', document.getElementById('points').value);
            payload.append('bonus', document.getElementById('bonus').value);
            payload.append('mode', 'manual');

            getRank(payload);
        }
    }

    const pilotIdInput = document.getElementById('pilotId');
    function idValidation() {
        const value = pilotIdInput.value;

        if (value.length === 4) {
            pilotIdInput.classList.remove('is-danger');
            pilotIdInput.classList.add('is-success');

            const payload = new FormData();

            payload.append('pilotId', document.getElementById('pilotId').value);
            payload.append('mode', 'id');

            document.getElementById('oldRank').textContent = "Please wait...";
            document.getElementById('newRank').textContent = "Please wait..."

            document.getElementById('oldEpaulette').src = "../public/images/loading.gif"
            document.getElementById('newEpaulette').src = "../public/images/loading.gif"

            getRank(payload);
        } else {
            pilotIdInput.classList.add('is-danger');
            pilotIdInput.classList.remove('is-success');
        }
    }

    inputs.forEach((input) => {
        if (input.name === 'pilotId') {
            input.addEventListener('input', idValidation);
        } else {
            input.addEventListener('input', manualInputValidation);
        }
    });

    function hideClassElements(className) {
        const elements = document.querySelectorAll(`.${className}`);

        elements.forEach(element => {
            element.classList.add('is-hidden');
        });
    }

    function showClassElements(className) {
        const elements = document.querySelectorAll(`.${className}`);

        elements.forEach(element => {
            element.classList.remove('is-hidden');
        });
    }

    const switchButton = document.getElementById('switchButton')
    switchButton.addEventListener('click', () => {
        switch (formMode) {
            case 'idInput':
                hideClassElements('idInput');
                showClassElements('manualInput');
                switchButton.textContent = 'Switch to fetch from pilot ID';
                formMode = 'manualInput';
            break;

            case 'manualInput':
                showClassElements('idInput');
                hideClassElements('manualInput');
                switchButton.textContent = 'Switch to manual input';
                formMode = 'idInput';
            break;
        }

        document.getElementById('oldRank').textContent = "Enter your details!";
        document.getElementById('newRank').textContent = "Enter your details!"

        document.getElementById('oldEpaulette').src = "../public/images/loading.gif"
        document.getElementById('newEpaulette').src = "../public/images/loading.gif"
    });
});
