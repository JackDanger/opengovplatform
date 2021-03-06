require 'rubygems'
#Load WATIR
require 'fileutils'
require 'lib/selenium_support'
# Load WIN32OLE library
require 'win32ole'
require 'Win32API'
#Load the win32 library
require 'win32/clipboard'
include Win32
#require 'InputRepository/captcha.rb'
require 'InputRepository/Config.rb'
#include 'Suite'
#PRE REQUISITES :-
#Login Credentials, Project Creation data

describe "Enable Captcha" do
  before(:all) do
      `Taskkill /IM firefox.exe /F`
      driver = Selenium::WebDriver.for :firefox, :profile => "Selenium"
      @browser = Watir::Browser.new driver
      @browser.goto("#{$CMS_Site_URL}")
  end


#To Login As CMS_Admin
  it "To Enter User Email address and Password" do
      @browser.text_field(:id,"edit-name").set("#{$CMS_Admin_User_Email}")
      @browser.text_field(:id,"edit-pass").set("#{$CMS_Admin_User_Passwd}")
      @browser.button(:id, "edit-submit").click 
	sleep 15
      @browser.text.should include("Welcome to Open Government Platform (OGPL)")
      puts "CMS Admin User Logged in Successfully"
      end   
      
  
  
     it "To Navigate to Manage Captcha" do
      @browser.goto("#{$Site_URL}/admin/user/captcha")
      sleep 5
      @browser.text.should include('CAPTCHA')
	
       puts "Successfully Navigated to Captcha Page"
  
      end
            
it "To Enable Captch" do
  @browser.select_list(:id, "edit-captcha-form-id-overview-captcha-captcha-points-comment-form-captcha-type").select("reCAPTCHA (from module recaptcha)")
  @browser.select_list(:id, "edit-captcha-form-id-overview-captcha-captcha-points-contact-mail-page-captcha-type").select("reCAPTCHA (from module recaptcha)")
  @browser.select_list(:id, "edit-captcha-form-id-overview-captcha-captcha-points-contact-owner-form-captcha-type").select("reCAPTCHA (from module recaptcha)")
  @browser.select_list(:id, "edit-captcha-form-id-overview-captcha-captcha-points-feedback-form-captcha-type").select("reCAPTCHA (from module recaptcha)")
  @browser.select_list(:id, "edit-captcha-form-id-overview-captcha-captcha-points-feedback-node-form-captcha-type").select("reCAPTCHA (from module recaptcha)")
  @browser.select_list(:id, "edit-captcha-form-id-overview-captcha-captcha-points-forward-form-captcha-type").select("reCAPTCHA (from module recaptcha)")
  
  @browser.button(:id, "edit-submit").click 
  sleep 5
      end
  
        
 it "Logout from CMS" do
     @browser.link(:text,"Log Out").click
   
 end 
      
    after(:all) do
        @browser.close
        `Taskkill /IM firefox.exe /F`
        puts "Test has completed"
  end
end