package database;
import java.sql.*;

public class Main {
	public static void main(String args[])
	{
		try {
			Class.forName("oracle.jdbc.driver.OracleDriver");  
			Connection con = DriverManager.getConnection("jdbc:oracle:thin:@localhost:1522:ug", "ora_w5y9a", "a20030145");
			Statement smt = con.createStatement();
			
			ResultSet rs = smt.executeQuery("select * from Author");
		
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}

}
