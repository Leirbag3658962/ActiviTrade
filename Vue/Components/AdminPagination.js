document.addEventListener('DOMContentLoaded', function() {
    
    const divGauche = document.getElementById('divGauche');
    const barreDroite = document.querySelector('.barreDroite');

    const modal = document.getElementById('add-modal');
    const modalFormContent = document.getElementById('modal-form-content');
    const closeModalButton = modal ? modal.querySelector('.close-modal-button') : null; 

    if (divGauche && barreDroite) {
        divGauche.addEventListener('click', function(event) {
            // Vérifie la click
            if (event.target && event.target.matches('a.deroulement')) {
                event.preventDefault(); 

                const nomTable = event.target.id; 
                chargerTable(nomTable, barreDroite)
                
                const formData = new FormData();
                formData.append('table', nomTable);

                
                fetch('AdminGetTable.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    //En cas d'erreur
                    if (!response.ok) {
                        throw new Error('Erreur réseau ou serveur: ' + response.statusText);
                    }
                    return response.text();
                })
                .then(html => {
                    barreDroite.innerHTML = html;
                })

                //En cas d'erreur
                .catch(error => {
                    console.error('Erreur lors de la récupération des données:', error);
                    barreDroite.innerHTML = '<p style="color: red;">Erreur lors du chargement des données : ' + error.message + '</p>';
                });
            }
        });
    } else {
        console.error("Élément #divGauche ou .barreDroite non trouvé.");
    }
    
    if (barreDroite) {
        barreDroite.addEventListener('click', function(event) {
            const clickedRow = event.target.closest('tr[data-pk-value]');

            if (clickedRow) {

                handleRowDelete(clickedRow); 
                return;

                // dataset contient tous les attributs d'un élément
                const pkValue = clickedRow.dataset.pkValue; 
                const tableName = clickedRow.dataset.table; 
                
                //En cas d'erreur
                if (!pkValue || !tableName) {
                    console.error("Attributs data-pk-value ou data-table manquants sur la ligne.", clickedRow);
                    return; 
                }

                
                if (confirm(`Voulez-vous vraiment supprimer cet élément de la table "${tableName}" (ID: ${pkValue}) ?`)) {

                    
                    const formData = new FormData();
                    formData.append('table', tableName);
                    formData.append('pkValue', pkValue);

                    
                    fetch('AdminDeleteRow.php', { 
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json()) // Attendre une réponse JSON
                    .then(data => {
                        if (data.success) {
                            setTimeout(() => {
                                clickedRow.remove(); 
                                //console.log(data.message || 'Suppression réussie côté serveur.');
                                
                            }, 300); // Attendre la fin de l'animation (si utilisée)

                        } else {
                            //En cas d'erreur
                            alert('Erreur lors de la suppression : ' + (data.message || 'Erreur inconnue.'));
                            console.error('Erreur serveur suppression:', data);
                        }
                    })
                    .catch(error => {
                        //En cas d'erreur
                        alert('Erreur de communication avec le serveur pour la suppression.');
                        console.error('Erreur fetch suppression:', error);
                    });
                } else {
                    // L'utilisateur a cliqué sur "Annuler"
                    console.log('Suppression annulée par l\'utilisateur.');
                }
            }
            if (event.target && event.target.matches('#BoutonAjouter')) {
                const buttonElement = event.target;
                const tableName = buttonElement.dataset.table;

                if(tableName){
                    displayAddForm(tableName);
                }else { 
                    console.error("data-table manquant sur #BoutonAjouter."); 
                }
            }
        });
    }

    if (modal && closeModalButton) {
        closeModalButton.addEventListener('click', function() {
            modal.style.display = 'none';
            modalFormContent.innerHTML = ''; 
        });
        modal.addEventListener('click', function(event) {
            if (event.target === modal) { 
                modal.style.display = 'none';
                modalFormContent.innerHTML = '';
            }
        });
    }

    if (modal) {
        modal.addEventListener('submit', function(event) {
             
            if (event.target && event.target.matches('#add-entity-form')) {
                event.preventDefault(); 
                handleFormSubmit(event.target); 
            }
        });

        
        modal.addEventListener('click', function(event) {
            if (event.target && event.target.matches('#cancel-add-button')) {
                 modal.style.display = 'none';
                 modalFormContent.innerHTML = ''; 
            }
        });
    }

    

}); 



function chargerTable(nomTable, containerElement) {
    // Affiche un indicateur de chargement (optionnel)
    containerElement.innerHTML = '<p>Chargement des données pour la table "' + nomTable + '"...</p>';

    const formData = new FormData();
    formData.append('table', nomTable);

    fetch('AdminGetTable.php', { 
        method: 'POST',
        body: formData
    })

    //En cas d'erreur
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau ou serveur: ' + response.statusText);
        }
        return response.text();
    })

    .then(html => {
        containerElement.innerHTML = html;
    })
    //En cas d'erreur
    .catch(error => {
        console.error('Erreur lors de la récupération des données:', error);
        containerElement.innerHTML = '<p style="color: red;">Erreur lors du chargement des données : ' + error.message + '</p>';
    });
}

function displayAddForm(tableName) {
    const modal = document.getElementById('add-modal');
    const modalFormContent = document.getElementById('modal-form-content');
    if (!modal || !modalFormContent) {
        console.error("Modal ou conteneur de formulaire non trouvé.");
        return;
    }

    modalFormContent.innerHTML = '<p>Chargement du formulaire...</p>';
    modal.style.display = 'flex'; 

    const formData = new FormData();
    formData.append('table', tableName);

    fetch('AdminGetForm.php', { method: 'POST', body: formData })
        .then(response => {
            if (!response.ok) { throw new Error('Erreur réseau formulaire: ' + response.statusText); }
            return response.text();
        })
        .then(formHtml => {
            modalFormContent.innerHTML = formHtml; 
        })
        .catch(error => {
            console.error('Erreur chargement formulaire:', error);
            modalFormContent.innerHTML = '<p style="color: red;">Erreur chargement formulaire: ' + error.message + '</p>';
        });
}


function handleFormSubmit(formElement) {
    const modal = document.getElementById('add-modal');
    const barreDroite = document.querySelector('.barreDroite'); 
    const tableName = formElement.dataset.table;
    if (!tableName || !barreDroite) {
        alert("Erreur interne: Table cible non définie ou conteneur principal manquant.");
        return;
    }

    const formData = new FormData(formElement); 
    formData.append('table', tableName); 


    fetch('AdminInsertion.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Ajout réussi !');
                if(modal) modal.style.display = 'none'; 
                chargerTable(tableName, barreDroite); 
            } else {
                alert('Erreur lors de l\'ajout : ' + (data.message || 'Erreur inconnue.'));
            }
        })
        .catch(error => {
            alert('Erreur de communication avec le serveur lors de l\'ajout.');
            console.error('Erreur fetch ajout:', error);
        })
        .finally(() => {
             
             if (submitButton) submitButton.disabled = false;
        });
}


function handleRowDelete(clickedRow) {
     const pkValue = clickedRow.dataset.pkValue;
     const tableName = clickedRow.dataset.table;

     if (!pkValue || !tableName) {
         console.error("Infos manquantes pour suppression.", clickedRow);
         return;
     }

     if (confirm(`Voulez-vous vraiment supprimer cet élément de la table "${tableName}" (ID: ${pkValue}) ?`)) {
         const formData = new FormData();
         formData.append('table', tableName);
         formData.append('pkValue', pkValue);

         fetch('AdminDeleteRow.php', { 
             method: 'POST',
             body: formData
         })
         .then(response => response.json())
         .then(data => {
             if (data.success) {
                 clickedRow.style.transition = 'opacity 0.3s ease-out'; 
                 clickedRow.style.opacity = '0';
                 setTimeout(() => { clickedRow.remove(); }, 300);
             } else {
                 alert('Erreur suppression : ' + (data.message || 'Erreur inconnue.'));
                 console.error('Erreur serveur suppression:', data);
             }
         })
         .catch(error => {
             alert('Erreur communication suppression.');
             console.error('Erreur fetch suppression:', error);
         });
     } else {
         console.log('Suppression annulée.');
     }
}