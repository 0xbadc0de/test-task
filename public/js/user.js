/**
 * User class
 * @type {{init: User.init, deleteUser: User.deleteUser, createUser: User.createUser, deleteUserModal: User.deleteUserModal}}
 */
const User = {

    /**
     * Creates an user
     * @param form
     */
    createUser: (form) => {
        console.log('[User] createUser Form:', form);

        const XHR = new XMLHttpRequest();
        const FD = new FormData(form);

        XHR.addEventListener('load', ( event ) => {
            alert('User created');

            // Reset form values (for ajax)
            const name = document.getElementById('userName')
            name.value = ''
            name.focus()

            document.getElementById('userEmail').value = ''

            // Finally
            location.reload();
        });

        XHR.addEventListener('error', ( event ) => {
            alert('Server error occurred :(');
        });

        XHR.open('POST', '/post');
        XHR.send(FD);
    },

    /**
     * Removes an user by position in object
     * @param position
     */
    deleteUser: (position) => {
        console.log('[User] deleteUser Position=', position);

        const XHR = new XMLHttpRequest();

        XHR.addEventListener('load', ( event ) => {
            alert('User deleted');

            // Finally
            location.reload();
        });

        XHR.open('DELETE', '/delete?position=' + position);
        XHR.send();
    },

    /**
     * Delete user confirmation window
     * @param position
     */
    deleteUserModal: (position) => {
        const r = confirm('Are you sure to remove a user?');
        if (r == true) {
            User.deleteUser(position);
        }
    },

    /**
     * Setup class
     */
    init: () => {
        console.log('[User] init()')

        let userForm = document.getElementById('user-form');
        userForm.addEventListener('submit', (e) => {
            e.preventDefault();

            User.createUser(e.target);
        });

        const elements = document.querySelectorAll('#user-table button[data-action="remove"]')
        for (const btn of elements) {
            btn.addEventListener('click', (e) => {
                if (e.target.dataset.position !== undefined) {
                    User.deleteUserModal(e.target.dataset.position);
                }
            });
        }
    }
}

User.init();