let currentlyEditingCell = null;

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

                
                fetch('../Pages/AdminGetTable.php', {
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
            const target = event.target;
            
            if (target && target.matches('td.editable-cell')) {
                // Éviter ré-éditer
                if (target.querySelector('input.inline-edit-input')) {
                    return;
                }
                // Gérer si édition en cours
                 if(currentlyEditingCell && currentlyEditingCell.inputElement) {
                    currentlyEditingCell.inputElement.blur(); 
                    if (document.body.contains(currentlyEditingCell.inputElement)) return; 
                 }
                 makeCellEditable(target); 
                return; 
            }

            if (target && target.matches('#BoutonAjouter')) {
                const tableName = target.dataset.table;
                if (tableName) { displayAddForm(tableName); }
                else { console.error("data-table manquant sur #BoutonAjouter."); }
                return;
            }

            const clickedRow = target.closest('tr[data-pk-value]');
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

                    
                    fetch('../Pages/AdminDeleteRow.php', { 
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
    containerElement.innerHTML = '<p>Chargement des données pour la table "' + nomTable + '"...</p>';

    const formData = new FormData();
    formData.append('table', nomTable);

    fetch('../Pages/AdminGetTable.php', { 
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

    fetch('../Pages/AdminGetForm.php', { method: 'POST', body: formData })
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


    fetch('../Pages/AdminInsertion.php', { method: 'POST', body: formData })
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

         fetch('../Pages/AdminDeleteRow.php', { 
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
function makeCellEditable(cellElement) {
    if (!cellElement || (currentlyEditingCell && currentlyEditingCell.cell === cellElement)) return;
    if(currentlyEditingCell && currentlyEditingCell.inputElement) {
        currentlyEditingCell.inputElement.blur();
        if (document.body.contains(currentlyEditingCell.inputElement)) return;
    }

    const originalValue = cellElement.textContent.trim();
    const columnName = cellElement.dataset.columnName;
    if (!columnName) { console.error("data-column-name manquant", cellElement); return; }

    const inputElement = document.createElement('input');
    inputElement.type = 'text'; // Adapter type
    inputElement.value = originalValue;
    inputElement.classList.add('inputModif');

    cellElement.innerHTML = '';
    cellElement.appendChild(inputElement);
    inputElement.focus();
    inputElement.select();

    currentlyEditingCell = { cell: cellElement, inputElement: inputElement, originalValue: originalValue, columnName: columnName };
    inputElement.addEventListener('blur', handleEditBlur);
    inputElement.addEventListener('keydown', handleEditKeyDown);
}

function handleEditBlur(event) {
    setTimeout(() => { 
        if (currentlyEditingCell && event.target === currentlyEditingCell.inputElement) {
            saveCellUpdate();
        }
    }, 150);
}

function handleEditKeyDown(event) {
    if (!currentlyEditingCell || event.target !== currentlyEditingCell.inputElement) return;
    if (event.key === 'Enter') { event.preventDefault(); saveCellUpdate(); }
    else if (event.key === 'Escape') { revertCell(currentlyEditingCell.originalValue); }
}

function saveCellUpdate() {
    if (!currentlyEditingCell) return;

    const cell = currentlyEditingCell.cell;
    const inputElement = currentlyEditingCell.inputElement;
    const newValue = inputElement.value.trim();
    const originalValue = currentlyEditingCell.originalValue;
    const columnName = currentlyEditingCell.columnName;
    const rowElement = cell.closest('tr[data-pk-value]');
    const pkValue = rowElement ? rowElement.dataset.pkValue : null;
    const tableName = rowElement ? rowElement.dataset.table : null;

    const editingInfo = { cell, inputElement, originalValue };
    currentlyEditingCell = null; 
    inputElement.disabled = true; 

    if (!pkValue || !tableName || !columnName) {
        alert("Erreur: Infos manquantes pour sauvegarde.");
        revertCell(originalValue, editingInfo.cell, editingInfo.inputElement); return;
    }
    if (newValue === originalValue) {
        revertCell(originalValue, editingInfo.cell, editingInfo.inputElement); return;
    }

    const formData = new FormData();
    formData.append('table', tableName);
    formData.append('pkValue', pkValue);
    formData.append('column', columnName);
    formData.append('value', newValue);

    fetch('../Pages/AdminUpdateRow.php', { method: 'POST', body: formData }) 
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                editingInfo.cell.innerHTML = '';
                editingInfo.cell.textContent = data.newValue ?? newValue;
                 // Feedback visuel optionnel
                editingInfo.cell.classList.add('update-success');
                setTimeout(() => editingInfo.cell.classList.remove('update-success'), 1500);
            } else {
                alert('Erreur mise à jour: ' + (data.message || 'Inconnue.'));
                revertCell(originalValue, editingInfo.cell, editingInfo.inputElement);
            }
        })
        .catch(error => {
            alert('Erreur communication mise à jour.');
            console.error('Erreur fetch update:', error);
            revertCell(originalValue, editingInfo.cell, editingInfo.inputElement);
        });
}

function revertCell(originalValue, cell = currentlyEditingCell?.cell, input = currentlyEditingCell?.inputElement) {
    if (cell) {
        cell.innerHTML = '';
        cell.textContent = originalValue;
    }
    if (currentlyEditingCell && currentlyEditingCell.cell === cell) {
        currentlyEditingCell = null; 
    }
}