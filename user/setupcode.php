<?php 
session_start();
if ($_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';

$userId = $_SESSION['user_id'];

// Check if the code exists in the database
$codeQuery = "SELECT user_codes.code FROM user_codes JOIN users ON users.user_id = $userId";
$result = mysqli_query($con, $codeQuery);

if (!$result) {
    die('Error fetching codes: '. mysqli_error($con));
}

$data  = mysqli_fetch_assoc($result);

$code = $data['code'];

echo $code;

// Generate a new code if the user does not have one
if (($data['code'])) {
    echo "No code found";
    // if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_code'])) {
        
    //     $code = $_POST['code']; //
    //     $confirm_code = $_POST['confirm_code']; 

    //     if($code != $confirm_code){
    //         $error = "The codes do not match.";
    //         $_SESSION['generated_code'] = '';
    //         $_SESSION['error'] = $error;
    //         header('Location: ./dashboard.php');
    //     }

    //     // Insert the generated code into the database (optional)
    //     $insertCodeQuery = "UPDATE user_codes SET code = '$code' WHERE user_id = $userId";
    //     mysqli_query($con, $insertCodeQuery);
    // }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Popup</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Optional: Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 flex h-screen">

    <div class="d-flex">
        <?php include('../components/sidenav.php'); ?>
    </div>

    <!-- Modern Popup Modal -->
    <!-- <div x-data="{ showModal: false, generatedCode: '<?php echo isset($_SESSION['generated_code']) ? $_SESSION['generated_code'] : ''; ?>' }" x-init="setTimeout(() => showModal = true, 3000)">
        <!-- Modal Background -->
        <div 
            x-show="showModal" 
            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;">
            
            <!-- Modal Content -->
            <div 
                class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative"
                x-show="showModal"
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="scale-95"
                x-transition:enter-end="scale-100"
                x-transition:leave="transition transform ease-in duration-200"
                x-transition:leave-start="scale-100"
                x-transition:leave-end="scale-95"
            >
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Setup Code</h3>
                    <button 
                        @click="showModal = false"
                        class="text-gray-400 hover:text-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">
                        Please set up your code to proceed. You can copy and paste the generated code below.
                    </p>
                    <p class="mt-2 font-semibold text-gray-800">
                        Code: <span class="bg-gray-200 p-2 rounded-md"><?php echo isset($_SESSION['generated_code']) ? $_SESSION['generated_code'] : '---'; ?></span>
                    </p>
                </div>
                <form method="POST" action="" class="mt-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-600">Enter Code</label>
                        <input type="text" id="code" name="code" class="mt-1 block w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mt-4">
                        <label for="confirm_code" class="block text-sm font-medium text-gray-600">Confirm Code</label>
                        <input type="text" id="confirm_code" name="confirm_code" class="mt-1 block w-full p-2 border rounded-md" required>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button 
                            @click="showModal = false"
                            type="button"
                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Close
                        </button>
                        <button type="submit" name="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Submit Code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->

    <!-- Example JavaScript -->
    <!-- <script>
        // Optional JavaScript to handle form submission and matching code
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            const code = document.getElementById('code').value;
            const confirmCode = document.getElementById('confirm_code').value;
            const generatedCode = "<?php echo $_SESSION['generated_code']; ?>";

            if (code !== confirmCode) {
                alert("The codes do not match!");
                event.preventDefault();
            }
            if (code !== generatedCode) {
                alert("Incorrect code!");
                event.preventDefault();
            }
        });
    </script> -->

</body>
</html>
