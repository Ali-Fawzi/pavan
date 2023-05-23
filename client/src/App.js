import {Route, Routes} from "react-router-dom";
import Login from "./pages/Login";
import ProtectedRoute from "./utils/protectedRoute";
import NotFound from "./pages/error/NotFound";
import AdminDashboard from "./pages/admin/AdminDashboard";
import DoctorDashboard from "./pages/doctor/DoctorDashboard";
import SecretaryDashboard from "./pages/secertary/SecretaryDashboard";
import Users from "./pages/admin/Users";
import Expenses from "./pages/admin/Expenses";
import Account from "./pages/Account";

export default function App() {

  return (
      <Routes>
          <Route path="/" element={<Login />}/>
          <Route path="/admin-dashboard" element={<ProtectedRoute component={AdminDashboard} requiredRole="admin"/>}/>
          <Route path="/admin-users" element={<ProtectedRoute component={Users} requiredRole="admin"/>}/>
          <Route path="/admin-expenses" element={<ProtectedRoute component={Expenses} requiredRole="admin"/>}/>
          <Route path="/doctor-dashboard" element={<ProtectedRoute component={DoctorDashboard} requiredRole="doctor" />}/>
          <Route path="/secretary-dashboard" element={<ProtectedRoute component={SecretaryDashboard} requiredRole="secretary" />}/>

          <Route path="/account" element={<Account/>}/>
          <Route path="*" element={<NotFound />} />
      </Routes>
  )
}