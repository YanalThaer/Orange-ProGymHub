@extends('layouts.public')
@section('title', 'ProGymHome')
@section('content')
<style>
    body {
        background-color: #121212;
        color: #ffffff;
    }

    .edit-container {
        display: flex;
        max-width: 1000px;
        margin: auto;
        padding: 2rem;
    }

    .sidebar {
        width: 250px;
        margin-right: 2rem;
    }

    .sidebar ul {
        list-style: none;
        padding-left: 0;
    }

    .sidebar li {
        padding: 10px 0;
        color: #ccc;
        cursor: pointer;
    }

    .sidebar li.active,
    .sidebar li:hover {
        color: #ff0000;
        font-weight: bold;
    }

    .content {
        flex-grow: 1;
        background-color: #1f1f1f;
        border-radius: 10px;
        padding: 2rem;
    }

    .form-control {
        background-color: #2c2c2c;
        color: white;
        border: 1px solid #444;
    }

    .form-control:focus {
        border-color: #ff0000;
        box-shadow: none;
    }

    .btn-primary {
        background-color: #ff0000;
        border-color: #ff0000;
    }

    .btn-primary:hover {
        background-color: #cc0000;
        border-color: #cc0000;
    }

    .profile-pic {
        width: 100px;
        height: 100px;
        background-color: #888;
        color: #fff;
        font-weight: bold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1rem;
    }

    .btn-outline {
        background-color: transparent;
        border: 1px solid #888;
        color: #ccc;
        margin-right: 10px;
    }

    .btn-outline:hover {
        background-color: #333;
        color: white;
    }
</style>
<div>

<div class="edit-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <li class="active">Edit Profile</li>
            <li>Password</li>
            <li>Social Profiles</li>
            <li>Billing</li>
            <li style="color: red; margin-top: 20px;">Delete Account</li>
        </ul>
    </div>

    <!-- Form Content -->
    <div class="content">
        <h4 class="mb-4">Edit Profile</h4>

        <div class="text-center mb-4">
            <div class="profile-pic">O</div>
            <button class="btn btn-outline">Upload new picture</button>
            <button class="btn btn-outline">Delete</button>
        </div>

        <form>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" value="ola yousef">
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" placeholder="Enter location">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" placeholder="Enter address">
            </div>

            <button type="submit" class="btn btn-primary mt-3 w-100">Save Changes</button>
        </form>
    </div>
</div>

</div>
@endsection