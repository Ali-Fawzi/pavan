import { Navigate } from "react-router-dom";
import jwt_decode from "jwt-decode";

const ProtectedRoute = ({ component: Component, requiredRole }) => {
    const accessToken = localStorage.getItem("access_token");

    // Check if the access token exists
    if (!accessToken) {
        // Redirect to the login page if the access token doesn't exist
        return <Navigate to="/" />;
    }

    const decodedToken = jwt_decode(accessToken);
    const userRole = decodedToken.role;

    return userRole === requiredRole ? <Component /> : <Navigate to="/" replace={true} />;
};

export default ProtectedRoute;
