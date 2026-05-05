import axiosClient from "./axios-client";

const ticketApi = {
    // CRUD operations for tickets

    // Get all tickets with optional filters
    getTickets: (params) => {
        return axiosClient.get("/tickets", { params });
    },
    // Get a single ticket by ID
    getTicket: (id) => {
        return axiosClient.get(`/tickets/${id}`);
    },
    // Create a new ticket
    createTicket: (data) => {
        return axiosClient.post("/tickets", data);
    },  
    // Update an existing ticket
    updateTicket: (id, data) => {
        return axiosClient.put(`/tickets/${id}`, data);
    },
    // Delete a ticket
    deleteTicket: (id) => {
        return axiosClient.delete(`/tickets/${id}`);
    },
};
export default ticketApi;
