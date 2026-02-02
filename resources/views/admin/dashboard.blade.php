@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Welcome, Admin!')

@section('content')

    <!-- Cards -->
    <div class="cards">
        <div class="card">
            <h4>Total Users</h4>
            <h2>1,250</h2>
        </div>
        <div class="card">
            <h4>New Orders</h4>
            <h2>85</h2>
        </div>
        <div class="card">
            <h4>Earnings</h4>
            <h2>$5,320</h2>
        </div>
        <div class="card">
            <h4>Support Tickets</h4>
            <h2>18</h2>
        </div>
    </div>

    <!-- Middle Section -->
    <div class="content">
        <div class="panel">
            <h3>Sales Overview</h3>
            <div class="chart"></div>
        </div>

        <div class="panel orders">
            <h3>Recent Orders</h3>

            <div class="order">
                <span>Order #1023</span>
                <span>$120 <span class="badge completed">Completed</span></span>
            </div>

            <div class="order">
                <span>Order #1022</span>
                <span>$250 <span class="badge pending">Pending</span></span>
            </div>

            <div class="order">
                <span>Order #1021</span>
                <span>$310 <span class="badge completed">Completed</span></span>
            </div>

            <div class="order">
                <span>Michael Brown</span>
                <span>$310 <span class="badge completed">Completed</span></span>
            </div>

            <div class="order">
                <span>Emily White</span>
                <span>$85 <span class="badge processing">Processing</span></span>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="bottom">
        <div class="panel user-stats">
            <h3>User Statistics</h3>

            <div class="stats-wrapper">

                <div class="y-axis">
                    <span>6</span>
                    <span>4</span>
                    <span>2</span>
                    <span>0</span>
                </div>

                <div class="bars-container">

                    <div class="day-group">
                        <div class="bars">
                            <div class="bar new" style="height:60px;"></div>
                            <div class="bar active" style="height:80px;"></div>
                        </div>
                        <p>Mon</p>
                    </div>

                    <div class="day-group">
                        <div class="bars">
                            <div class="bar new" style="height:100px;"></div>
                            <div class="bar active" style="height:70px;"></div>
                        </div>
                        <p>Tue</p>
                    </div>

                    <div class="day-group">
                        <div class="bars">
                            <div class="bar new" style="height:110px;"></div>
                            <div class="bar active" style="height:75px;"></div>
                        </div>
                        <p>Wed</p>
                    </div>

                    <div class="day-group">
                        <div class="bars">
                            <div class="bar new" style="height:95px;"></div>
                            <div class="bar active" style="height:65px;"></div>
                        </div>
                        <p>Thu</p>
                    </div>

                    <div class="day-group">
                        <div class="bars">
                            <div class="bar new" style="height:120px;"></div>
                            <div class="bar active" style="height:85px;"></div>
                        </div>
                        <p>Fri</p>
                    </div>

                    <div class="day-group">
                        <div class="bars">
                            <div class="bar new" style="height:130px;"></div>
                            <div class="bar active" style="height:75px;"></div>
                        </div>
                        <p>Sat</p>
                    </div>

                </div>
            </div>

            <div class="legend">
                <div><span class="legend-box new"></span> New Users</div>
                <div><span class="legend-box active"></span> Active Users</div>
            </div>

        </div>
    </div>

@endsection
