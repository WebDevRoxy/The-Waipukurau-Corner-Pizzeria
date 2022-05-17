const AddPizza = document.getElementById("addPizzaBtn");
const olContainer = document.getElementById("olContainer");

AddPizza.addEventListener("click", AddNew);

//disables add button until form is valid. This prevents multiple invaldi rows being added
const form = document.getElementById('pizzaOrderForm');
form.addEventListener("change", () => {
    document.getElementById('addPizzaBtn').disabled = !form.checkValidity()
});

function AddNew(){
    const row = 
        `Pizza: <input list="pizzaList" name="selectedPizza" placeholder="Pizza" required
        onclick="javascript: this.value = ''" >
        Number: <input type="number" name="numPizzas" required min="0" max="10">
        Delete Item: <input type="checkbox" onclick='DeletePizza(this)'>`

    const newLi = document.createElement("li");
    newLi.classList.add("pizzaSelection");
    newLi.innerHTML = row;

    olContainer.appendChild(newLi);

    //disable the add button to prevent multiple blabk rows being added
    document.getElementById('addPizzaBtn').disabled = true;
}

function DeletePizza(event) {
    //delete row if the number of row is greater than one
    if (olContainer.children.length > 1) {
        let pizza = event.parentElement;    
        olContainer.removeChild(pizza);
    }
}
