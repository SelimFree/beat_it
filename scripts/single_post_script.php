<script>
    //single post

    function loadMoreComments(event) {
        let page = parseInt(event.target.dataset.page) + 1;
        let id = event.target.dataset.id;
        event.target.dataset.page = page;
        const location = window.location.pathname.split("/").pop();
        fetch(`${location}?page=${page}&id=${id}`, {
                method: 'GET',
            })
            .then(response => {
                // Check if the request was successful (status code 200)
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Parse the response as JSON or text, depending on the content type
                return response.text(); // or response.text() if it's not JSON
            })
            .then(data => {
                // Handle the data from the response
                const tempElement = document.createElement('div');
                tempElement.innerHTML = data;
                const newComments = tempElement.querySelector(".comments-content");
                const currentComments = document.querySelector(".comments-content");
                currentComments.innerHTML += newComments.innerHTML;
            })
            .catch(error => {
                // Handle errors during the fetch operation
                console.error('Fetch error:', error);
            });
    }

    function postComment(event) {
        let id = event.target.dataset.id;
        const inputField = event.target.previousElementSibling;
        let comment = inputField.value;

        const formData = new FormData();
        formData.append("id", id);
        formData.append("comment", comment);
        formData.append("action", "post_comment");

        fetch("single_post.php", {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                // Check if the request was successful (status code 200)
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                // Parse the response as JSON or text, depending on the content type
                return response.text(); // or response.text() if it's not JSON
            })
            .then(data => {
                // Handle the data from the response
                location.reload();
            })
            .catch(error => {
                // Handle errors during the fetch operation
                console.error('Fetch error:', error);
            });
    }

    //single post
</script>