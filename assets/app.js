/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

const addTaskForm = document.querySelector('#addTaskForm');

document.addEventListener('DOMContentLoaded', (_) => {
    addTaskForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(addTaskForm);
        const response = await fetch('http://127.0.0.1:8000/task/new', {method: "POST", body: formData});

        if (!response.ok) {
            throw new Error('Response status:' + response.status);
        } else {
            const json = await response.json();

            const taskList = document.querySelector('#taskList');

            const newTask = document.createElement('div');
            newTask.innerHTML = `
                <a class="text-decoration-none text-body" href="task/{{task.getId()}}">
                    <li class="task p-2 my-2 shadow-sm border rounded">
                    <div class="fs-6">${json['title']}</div>
                    <small class="text-light-emphasis fw-light">Due ${formatDate(json['deadline'])}</small>
                    </li>
                </a>`;
            taskList.appendChild(newTask);
        }
    });
});

function formatDate(dateString){
    console.log(dateString);
    const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    const date = new Date(dateString);
    const dayName = days[date.getDay()];
    const monthName = months[date.getMonth()];
    const dayOfMonth = date.getDate();

    return `${dayName}, ${monthName} ${dayOfMonth}`;

}
