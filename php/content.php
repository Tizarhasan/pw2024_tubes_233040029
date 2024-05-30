<?php
session_start();

$conn = mysqli_connect("localhost:3306", "root", "root", "pw2024_tubes_233040029");

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}

$query = "SELECT * FROM content";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .card {
            cursor: pointer;
        }
    </style>
</head>
<body>
<section class="nav mb-5">
        <?php include '../NavigationBar/nav1.php'; ?>
    </section>
    <div class="container py-5">
        <h2 class="mb-4">Content Gallery</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (mysqli_num_rows($result) > 0):?>
                <?php while ($row = mysqli_fetch_assoc($result)):?>
                    <div class="col">
                        <div class="card" data-bs-toggle="modal" data-bs-target="#contentModal"
                             data-description="<?php echo htmlspecialchars($row['description']);?>"
                             data-image="../assets/img/<?php echo htmlspecialchars($row['image_path']);?>"
                             data-video="<?php echo htmlspecialchars($row['video_url']);?>">
                            <img src="../assets/img/<?php echo htmlspecialchars($row['image_path']);?>" class="card-img-top" alt="Image">
                            <div class="card-body">
                                <p class="card-text"><?php echo htmlspecialchars($row['description']);?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile;?>
            <?php else:?>
                <div class="col">
                    <p class="text-muted">No content available.</p>
                </div>
            <?php endif;?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentModalLabel">Content Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" class="img-fluid mb-3" alt="Image">
                    <p id="modalDescription"></p>
                    <div id="modalVideo" class="ratio ratio-16x9">
                        <iframe src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script>
        var contentModal = document.getElementById('contentModal');
        contentModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var description = button.getAttribute('data-description');
            var image = button.getAttribute('data-image');
            var video = button.getAttribute('data-video');

            var modalDescription = contentModal.querySelector('#modalDescription');
            var modalImage = contentModal.querySelector('#modalImage');
            var modalVideo = contentModal.querySelector('#modalVideo iframe');

            modalDescription.textContent = description;
            modalImage.src = image;

            // Extract the video ID from the full watch URL
            var url = new URL(video);
            var video_id = url.searchParams.get('v');

            // Use the video ID to construct the embed URL
            var embed_url = 'https://www.youtube.com/embed/' + video_id;
            modalVideo.src = embed_url;
        });

        contentModal.addEventListener('hidden.bs.modal', function (event) {
            var modalVideo = contentModal.querySelector('#modalVideo iframe');
            modalVideo.src = '';
        });
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
