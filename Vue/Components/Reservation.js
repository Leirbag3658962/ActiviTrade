const participants = [];

document.getElementById("self-participate").addEventListener("change", function () {
    const checked = this.checked;

    if (checked) {
        fetch("../../Modele/getUserInfo.php")
            .then(response => response.json())
            .then(data => {
                const { nom, prenom, dateNaissance } = data;
                const selfParticipant = { nom, prenom, dateNaissance, isSelf: true };

                const alreadyExists = participants.some(p => p.isSelf);
                if (!alreadyExists) {
                    participants.push(selfParticipant);
                    updateParticipantTable();
                }
            });
    } else {
        const index = participants.findIndex(p => p.isSelf);
        if (index !== -1) {
            participants.splice(index, 1);
            updateParticipantTable();
        }
    }
});

document.getElementById('saved-participant-select').addEventListener('change', function () {
    const selected = this.value;
    if (!selected) {
        document.getElementById('nom').value = '';
        document.getElementById('prenom').value = '';
        document.getElementById('dateNaissance').value = '';
        return;
    }

    const participant = JSON.parse(selected);
    document.getElementById('nom').value = participant.nom;
    document.getElementById('prenom').value = participant.prenom;
    document.getElementById('age').value = participant.dateNaissance; // 如果你要的是日期，保留这样；否则你也可以换成计算后的年龄
});

document.getElementById("Button").addEventListener("click", function () {
    const select = document.getElementById("saved-participant-select");
    const selectedValue = select.value;

    let participant;

    if (selectedValue) {
        participant = JSON.parse(selectedValue);
        select.value = "";
    } else {
        const nom = document.getElementById("nom").value.trim();
        const prenom = document.getElementById("prenom").value.trim();
        const dateNaissance = document.getElementById("dateNaissance").value.trim();

        if (!nom || !prenom || !dateNaissance) {
            alert("Veuillez remplir tous les champs.");
            return;
        }
        participant = { nom, prenom, dateNaissance };

    }

    const duplicate = participants.some(p =>
        p.nom === participant.nom &&
        p.prenom === participant.prenom &&
        p.dateNaissance === participant.dateNaissance
    );

    if (!duplicate) {
        participants.push(participant);
        updateParticipantTable();

        document.getElementById("nom").value = '';
        document.getElementById("prenom").value = '';
        document.getElementById("dateNaissance").value = '';
        select.value = '';
    } else {
        alert("Cet participant existe déja.")
    }
});

function updateParticipantTable() {
    const countElement = document.getElementById("participant-count");
    const tbody = document.getElementById("participant-body");

    countElement.textContent = `Nombre de participants: ${participants.length}`;

    tbody.innerHTML = '';

    if (participants.length === 0) {
        tbody.innerHTML = `<tr><td>Aucun participant pour le moment.</td></tr>`;
    } else {
        participants.forEach(p => {
            const row = document.createElement("tr");
            row.innerHTML = `<td>${p.nom} ${p.prenom} </td>`;
            tbody.appendChild(row);
        });
    }
}
