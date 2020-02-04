/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Used in homepage and manage user
const handleSearch = () => {
    var searchBar = document.getElementById("searchBar");

    if(searchBar) {
        searchBar.onkeyup = function() {
            var searchTxt = searchBar.value.toLowerCase();
            var table = document.getElementById(this.dataset.tableid);
            console.log(this.dataset.tableid);
            var tableRows = table.querySelectorAll("tbody tr");
        
            for(i = 0; i < tableRows.length; i++) {
                var row = tableRows[i];
                var searchColumns = row.getElementsByClassName("toSearchInto");
                var toHide = true;

                for(j = 0; j < searchColumns.length; j++) {
                    txtValue = searchColumns[j].textContent || searchColumns[j].innerText;
                    if(txtValue.toLowerCase().indexOf(searchTxt) > -1) {
                        toHide = false;
                    }
                }

                if(toHide) {
                    row.style.display = "none";
                } else {
                    row.style.display = "";
                }
            }
        }
    }
}

// Used in homepage and manage user
const orderTable = (tableId, columnNumber, asc) => {
    var table, tableRows, ordering, i, row, nextRow, shouldSwitch;

    table = document.getElementById(tableId);

    ordering = true;
    
    while(ordering) {
        ordering = false;
        tableRows = table.querySelectorAll(`tbody tr`);

        for(i = 0; i < (tableRows.length-1); i++) {
            shouldSwitch = false;

            row = tableRows[i];
            nextRow = tableRows[i+1];

            rowText = row.querySelector(`td:nth-child(${columnNumber})`).innerHTML;
            newRowText = nextRow.querySelector(`td:nth-child(${columnNumber})`).innerHTML;

            if(asc == true && rowText.toLowerCase() > newRowText.toLowerCase()) {
                shouldSwitch = true;
                break;
            }
            if(asc == false && newRowText.toLowerCase() > rowText.toLowerCase()) {
                shouldSwitch = true;
                break;
            }
        }
        if(shouldSwitch) {
            tableRows[i].parentNode.insertBefore(tableRows[i+1], tableRows[i]);
            ordering = true;
        }
    }

    var columnHeaders = table.querySelectorAll("thead th.orderAvailable");
    columnHeaders.forEach((columnHeader) => {
        columnHeader.classList.remove("ascending");
        columnHeader.classList.remove("descending");
    });
    var thisColumnHeader = table.querySelector(`thead th:nth-child(${columnNumber})`);
    thisColumnHeader.classList.add(asc ? "ascending" : "descending");
}

// Used in homepage and manage user
const handleOrder = () => {
    var elements = document.querySelectorAll(".orderAvailable");
    elements.forEach((element) => {
        element.onclick = function () {
            if(this.classList.contains("ascending")) {
                orderTable(this.dataset.tableid, this.dataset.column, false);
                this.classList.remove("ascending");
                this.classList.add("descending");
            } else {
                orderTable(this.dataset.tableid, this.dataset.column, true);
                this.classList.add("ascending");
                this.classList.remove("descending");
            }
        }
    })
}

// Used in Add/edit password and homepage
const handleShowHidePassword = () => {
    var elements = document.querySelectorAll(".passwordInputGroup .showHide");
    elements.forEach((element) => {
        element.onclick = function() {
            var inputPasswordId = this.dataset.inputpasswordid;

            if(this.dataset.isVisible == "false") {
                this.dataset.isVisible = "true";
                this.innerHTML = '<i class="far fa-eye"></i>';
                document.getElementById(inputPasswordId).type = 'password';
            } else {
                this.dataset.isVisible = "false";
                this.innerHTML = '<i class="far fa-eye-slash"></i>';
                document.getElementById(inputPasswordId).type = 'text';
            }
        }
    })
}

// Used on homepage
const handleAutoCopy = () => {
    var elements = document.querySelectorAll(".passwordInputGroup .directCopy");
    elements.forEach((element) => {
        element.onclick = function() {
            var inputPasswordId = this.dataset.inputpasswordid;
            var input = document.getElementById(inputPasswordId);
            input.select();
            input.setSelectionRange(0, 99999);

            if(input.type == "text") {
                document.execCommand("copy");
            } else {
                input.type = "text";
                document.execCommand("copy");
                input.type = "password";
            }
            this.classList.add("copied");
            setTimeout(() => {
                this.classList.remove("copied");
            }, 1000);
        }
    })
}

// Used on Add password
const randomPasswordGenerator = (min, max) => {
    var passwordChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz#@!%&()/";
    var randPwLen = Math.floor(Math.random() * (max - min + 1)) + min;
    var randPassword = Array(randPwLen).fill(passwordChars).map(function(x) { return x[Math.floor(Math.random() * x.length)] }).join('');
    return randPassword;
}

// Used on Add password
const handleGeneratePassword = () => {
    var element = document.getElementById("genRandomPass");

    if(element) {
        element.onclick = function() {
            password = randomPasswordGenerator(10,12);
            document.getElementById("password").value = password;
            document.getElementById("password-confirm").value = password;
        }
    }
}

// Used on Share password
const handleAddSharedUser = () => {
    var element = document.getElementById("addButton");

    if(element) {
        element.onclick = function() {
            selector = document.getElementById(this.dataset.selectorid);

            if(selector.value != "Undefined") {
                tableBody = document.getElementById(this.dataset.sharedusertableid);
                
                row = tableBody.dataset.row;

                count = tableBody.dataset.count;
                email = selector.options[selector.selectedIndex].text;
                userId = selector.value;

                newRow = row.replace(/%key%/g, count).replace(/%email%/g, email).replace(/%id%/g, userId);

                if(count == 0) {
                    tableBody.innerHTML = newRow;
                } else {
                    tableBody.innerHTML = tableBody.innerHTML + newRow;
                }

                tableBody.dataset.count = count + 1;
                selector.options[selector.selectedIndex] = null;

                selector.value = "Undefined";
            }
        }
    }
}

// Used on Share password
window.removeSharedUser = (button) => {
    line = document.getElementById(button.dataset.removable);
    select = document.getElementById(button.dataset.selectorid);
    input = document.querySelector("#"+button.dataset.removable+" input");

    option = select.dataset.defineoption;
    
    newOption = option.replace(/%email%/g, input.dataset.email).replace(/%id%/g, input.value);
    
    select.innerHTML = select.innerHTML + newOption;

    line.parentNode.removeChild(line);
}

// Used on homepage
const handleShowModalRemoveCredential = () => {
    var elements = document.querySelectorAll(".removeCredential");
    elements.forEach((element) => {
        element.onclick = function() {
            document.getElementById("removeLink").href = this.dataset.link;
            $("#removeModal").modal('show');
        }
    })
}

// Used on Manage user
const handleChangeStateOnChange = () => {
    var elements = document.querySelectorAll(".StateOnChange");
    elements.forEach((element) => {
        element.onchange = function() {
            changedInput = document.getElementById(this.dataset.onchangeid);
            changedInput.value = 1;
        }
    })
}

const initialize = () => {
    handleSearch();
    handleOrder();
    handleShowHidePassword();
    handleAutoCopy();
    handleGeneratePassword();
    handleAddSharedUser();
    handleShowModalRemoveCredential();
    handleChangeStateOnChange();
}

window.onload = initialize;