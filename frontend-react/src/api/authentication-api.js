import axiosClient from "./axios-client";

const authenticationApi = {
    // User login
    login: (credentials) => {   
        return axiosClient.post("/login", credentials);
    },
};

export default authenticationApi;