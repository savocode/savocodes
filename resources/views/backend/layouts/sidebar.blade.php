    <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="{{ public_url( user()->profile_picture_path ) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p>{{ user()->full_name_decrypted }}</p>
              <a  style="display: none;" href="{{ url('/') }}" target="_blank">(View front website)</a>
            </div>
          </div>
        <br>

          <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            {!! backend_sidebar_generator( constants('back.sidebar.menu'), [
                'users'              => ($isAdmin = user()->isAdmin()),
                'hospitals'          => $isAdmin,
                'professions'        => $isAdmin,
                'criteria'           => $isAdmin,
                'reports'            => false,
               // 'settings'           => $isAdmin,
                'referrals'          => $isEmployee = user()->isEmployee(),
                'hospital_physician' => $isEmployee,
               // 'settings'           => $isEmployee,
            ] ) !!}
          </ul>
    </section>
