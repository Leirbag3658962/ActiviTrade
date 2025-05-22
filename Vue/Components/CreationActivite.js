document.addEventListener('DOMContentLoaded',function(){
    const nom = document.getElementById('inputNom');
    const date = document.getElementById('inputDate');
    const adresse = document.getElementById('inputAdresse');
    const ville = document.getElementById('inputVille');
    const duree = document.getElementById('inputDuree');
    const categorie = document.getElementById('inputCategorie');
    const nbrparticipant = document.getElementById('inputNbrParticipant');
    const prix = document.getElementById("inputPrix");
    const public = document.getElementById("Public");
    const labpublic = document.getElementById("labPublic");
    const privee = document.getElementById("Privée");
    const labprivee = document.getElementById("labPrivée");
    // const description = document.getElementById("inputDescription");

    nom.addEventListener('input', function(){
        if(nom.value.length > 4){
            nom.style.borderColor = 'green';
        }else{
            nom.style.borderColor = 'red';
        }
    })
    date.addEventListener('input', function(){
        if(date.value.length > 0){
            date.style.borderColor = 'green';
        }else{
            date.style.borderColor = 'red';
        }
    })
    ville.addEventListener('input', function(){
        if(ville.value.length > 0){
            ville.style.borderColor = 'green';
        }else{
            ville.style.borderColor = 'red';
        }
    })
    adresse.addEventListener('input', function(){
        if(adresse.value.length > 0){
            adresse.style.borderColor = 'green';
        }else{
            adresse.style.borderColor = 'red';
        }
    })
    duree.addEventListener('input', function(){
        if(duree.value.length > 0){
            duree.style.borderColor = 'green';
        }else{
            duree.style.borderColor = 'red';
        }
    })
    categorie.addEventListener('input', function(){
        if(categorie.value.length > 0){
            categorie.style.borderColor = 'green';
        }else{
            categorie.style.borderColor = 'red';
        }
    })
    nbrparticipant.addEventListener('input', function(){
        if(nbrparticipant.value.length > 0){
            nbrparticipant.style.borderColor = 'green';
        }else{
            nbrparticipant.style.borderColor = 'red';
        }
    })
    prix.addEventListener('input', function(){
        if(prix.value.length > 0){
            prix.style.borderColor = 'green';
        }else{
            prix.style.borderColor = 'red';
        }
    })

    function updateRadioInput(){
        if(privee.checked == true){
            labprivee.style.color = 'green';
            labpublic.style.color = 'red';
        }else{
            labprivee.style.color = 'red';
            labpublic.style.color = 'green';
        }
    }

    privee.addEventListener('change', updateRadioInput);
    public.addEventListener('change', updateRadioInput);
    updateRadioInput();
})